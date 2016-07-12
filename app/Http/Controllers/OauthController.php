<?php
namespace App\Http\Controllers;
use Validator;
use QrCode;
use App\Console\Commands\common;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
/**
 * oauth
 */
class OauthController extends Controller {
    private $common;
    function __construct() {
        $this->common=new common();
    }
    

    /**
    * 验证请求参数
    * @param which display appKey redirect_url response_type
    * @return array
    */
    public function checkParam($param,$add_rules=[]){
        $message = [
            'required'          =>  '缺少 :attribute 参数',
            'username.required' =>  '请输入规范的道客账号',
            'password.required' => '请输入道客密码',
            'phone.numeric'     => '请输入规范的手机号码',
            'code.numeric'      => '请输入规范的验证码',
            'nickname.require'  => '请输入昵称',
        ];
        $rules = [
            'which'         =>  'required',
            'display'       =>  'required',
            'appKey'        =>  'required',
            'redirect_url'  => 'required|url'
        ];
        $validator = Validator::make($param,array_merge($rules,$add_rules),$message);
        return $validator;
    }
    /**
    * 验证扫码请求参数
    * @param appKey accountID access_token redirect_url sign
    * return array
    */
    public function checkQRParam($param,$add_rules=[]){
      $message = [
          'required'  =>  '缺少 :attribute 参数'
      ];
      $rules = [
          'accountID'     =>  'required',
          'access_token'  =>  'required',
          'appKey'        =>  'required',
          'redirect_url'  =>  'required'
      ];
      $validator = Validator::make($param,array_merge($rules,$add_rules),$message);
      return $validator;
    }
    
    /**
    * 获取二维码-扫码登录
    * @param appKey accountID access_token redirect_url
    * @return QR png
    */
    public function createQR(Request $request){
      $get_data = $request->all();
      unset($get_data['_url']);
      $check_param = $this->checkQRParam($get_data);
      if($check_param->fails()){
        return response()->json(['ERRORCODE' => 'ME10023', 'RESULT' => $check_param->errors()]);
      }
      if(!$this->common->getSecret($get_data['appKey'])){
        return response()->json(['ERRORCODE' => 'ME10002', 'RESULT' => "appKey error"]);
      }
      $request_data = array(
        'appKey'        => $get_data['appKey'],
        'accountID'     => $get_data['accountID'],
        'redirect_url'  => urlencode($get_data['url']),
        'access_token'  => $get_data['access_token']
      );
      $request_url = $this->common->createLinkString(url('/oauth/checkQR'),$this->common->getSignArray($request_data));
      return QrCode::format('png')->merge('/public/img/iconfont-renzheng.png')->size(300)->generate($request_url);
    }

    /**
    * 验证二维码-扫码登录
    * @param appKey sign access_token redirect_url accountID
    * @return views
    */
    public function checkQR(Request $request){
      $get_data = $request->all();
      unset($get_data['_url']);
      $add_rules = [
          'sign' => 'required'
      ];
      $check_param  = $this->checkQRParam($get_data,$add_rules);
      if($check_param->fails()){
        return response()->json(['ERRORCODE' => 'ME10023', 'RESULT' => $check_param->errors()]);
      }
      if(!$this->common->checkSign($get_data)){
        return response()->json(['ERRORCODE' => 'ME01019', 'RESULT' => 'sign is not match']);
      }
      $request_data = array(
        'appKey'        => $get_data['appKey'],
        'accountID'     => $get_data['accountID'],
        'accessToken'   => $get_data['access_token']
      );
      $href_url = $this->common->createLinkString(urldecode($get_data['redirect_url']),$request_data);
      return redirect($href_url);
    }

    
    /**
    * ajax提交后验证并登录
    * @param username password which ...
    * @return redirect url
    */
    public function checkLogin(Request $request){
        $add_rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $post_data    = $request->all();
        $check_param  = $this->checkParam($post_data,$add_rules);
        if($check_param->fails()){
            //参数验证错误 返回相应的登录页面
            return response()->json(['ERRORCODE' => '1000', 'RESULT' => '参数验证错误']);
        }
        $request_data = [
            'username'      => $post_data['username'],
            'daokePassword' => $post_data['password'],
            'appKey'        => $post_data['appKey']
        ];
        //请求 接口
        $url=config('app.request_url')['checkLogin'];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        //判断错误码
        if($body['ERRORCODE'] == "0"){
            $request_data = [
                'accountID' => $body['RESULT']['accountID'],
                'scope'     => 'userInfo',
                'appKey'    => $post_data['appKey']
            ];
            //调用接口
            $access_data=$this->common->getAccessToken($request_data);
            if($access_data['ERRORCODE']==0){
                $href_url = $this->common->createLinkString($post_data['redirect_url'],$access_data['RESULT']);
                Log::info('time: '.date('Y-m-d H:i:s',time()).' checkLogin href_url: '.$href_url);
                return response()->json(['ERRORCODE' => '0', 'RESULT' => $href_url]);
            }
        }else{
            Log::info('time: '.date('Y-m-d H:i:s',time()).' checkLogin RESULT: '.$body['RESULT']);
            return response($result);
        }
    }

    /**
    * 验证手机验证码,完成注册
    * @param phone code nickname password
    * @return redirect_url
    */
    public function registerUser(Request $request){
        $add_rules = [
            'username' => 'required',
            'password' => 'required',
            'nickname' => 'required',
        ];
        $post_data    = $request->all();
        //手机类型才验证code
        if($post_data['which'] == 'mobile'){
            $add_rules['code'] = 'required';
        }

        $session = $request->session()->all();
        $check_param  = $this->checkParam($post_data,$add_rules);
        //判断参数是否有错
        if($check_param->fails()){
            return response()->json(['ERRORCODE'=>'1000','RESULT'=>'参数验证错误']);
        }
        $request_data = [
            'daokePassword' => $post_data['password'],
            'nickname'      => $post_data['nickname'],
            'appKey'        => $post_data['appKey']
        ];
        if(!isset($post_data['password']{5})){
            return response()->json(['ERRORCODE'=>'1005','RESULT'=>'密码长度不能小于六位']);
        }
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $post_data['nickname'])){
            return response()->json(['ERRORCODE'=>'1011','RESULT'=>'名字必须为中文名']);
        }
        //判断是哪种注册方式
        if($post_data['which'] == 'username'){
            if(preg_match('/\d/', $post_data['username']{0})){
                return response()->json(['ERRORCODE'=>'1010','RESULT'=>'用户名首位不能为数字']);
            }
            $request_data['accountType']  = 1;
            $request_data['username']     = $post_data['username'];
        }elseif($post_data['which'] == 'mobile'){
            if(!preg_match('/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/',$post_data['username'])){
                return response()->json(['ERRORCODE' => '1009', 'RESULT' => '请输入规范的手机号']);
            }
            $request_data['accountType']  = 2;
            $request_data['mobile']       = $post_data['username'];
            if(!isset($session['verifyCode'])){
                return response()->json(['ERRORCODE'=>'2000','RESULT'=>'请先获取验证码']);
            }
            if(time() > $session['expired_time']){
                return response()->json(['ERRORCODE'=>'1003','RESULT'=>'验证码已过期']);
            }
            //判断验证码是否正确
            if($post_data['username'].$post_data['code'] != $session['verifyCode'] ){
                return response()->json(['ERRORCODE'=>'1001','RESULT'=>'您输入的验证码不正确']);
            }
            $request->session()->forget('verifyCode');
            $request->session()->forget('verifyCode');
        }elseif($post_data['which'] == 'email'){
            if(!preg_match('/^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/',$post_data['username'])){
                return response()->json(['ERRORCODE'=>'1008','RESULT'=>'请输入规范的邮箱']);
            }
            $request_data['accountType']  = 3;
            $request_data['userEmail']    = $post_data['username'];
        }
        $url=config('app.request_url')['addCustomAccount'];
        $result=$this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        //验证注册成功
        if($body['ERRORCODE'] == '0') {
            $request_data = [
                'accountID' => $body['RESULT']['accountID'],
                'scope'     => 'userInfo',
                'appKey'    => $post_data['appKey']
            ];
            //获取access_token 成功直接登录
            $access_data = $this->common->getAccessToken($request_data);
            if ($access_data['ERRORCODE'] == 0) {
                $body['RESULT'] = array_merge($body['RESULT'], $access_data['RESULT']);
                $href_url = $this->common->createLinkString($post_data['redirect_url'],$body['RESULT']);
                Log::info('time: '.date('Y-m-d H:i:s',time()).' registerUser  href_url: '.$href_url);
                return response()->json(['ERRORCODE' => '0', 'RESULT' => $href_url]);
            }
        }else{
            return response($result);
        }
    }
    /**
    * 获取登录的动态密码
    * $param phone _toknen
    * $return code
    */
    public function getDynamicPwd(Request $request){
        $phone = $request->input('phone','');
        if(!preg_match('/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/',$phone)){
            return response()->json(['ERRORCODE' => 'ME10023', 'RESULT' => '请输入规范的手机号']);
        }
        $request_data = [
            'mobile' => $phone,
            'appKey' => config('app.dev_config')['appKey']
        ];
        $url=config('app.request_url')['getDynamicPwd'];
        $result = $this->common->accessApi($request_data,$url);
        return response($result);
    }
    /**
    * 验证动态密码 并注册用户
    * @param phone verifycode
    * @return views
    */
    public function checkDynamicPwd(Request $request){
        $add_rules = [
            'phone' => 'required|numeric',
            'code'  => 'required|numeric'
        ];
        $post_data    = $request->all();
        $check_param  = $this->checkParam($post_data,$add_rules);
        if($check_param->fails()){
            return view($this->common->returnViews('login/login',$post_data['which']),['data' => $post_data])
                    ->withErrors($check_param->errors());
        }
        $request_data = [
            'mobile'        => $post_data['phone'],
            'verifyCode'    => $post_data['code'],
            'appKey'        => config('app.dev_config')['appKey']
        ];
        $url=config('app.request_url')['checkDynamicPwd'];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        if($body['ERRORCODE'] == "0"){
            $request_data = [
                'accountID' => $body['RESULT']['accountID'],
                'scope'     =>'userInfo',
                'appKey'    => $post_data['appKey']
            ];
            $access_data=$this->common->getAccessToken($request_data);
            $access_data['RESULT']=array_merge($body['RESULT'],$access_data['RESULT']);
            if($access_data['ERRORCODE']=="0"){
                $href_url = $this->common->createLinkString($post_data['redirect_url'],$access_data['RESULT']);
                return response()->json(['ERRORCODE'=>'0','RESULT'=>$href_url]);
            }
        }else{
            return response($result);
        }
    }
    /**
    * 获取验证手机号的验证码
    * @param phone
    * @return code
    */
    public function getVerifyCode(Request $request){
        $data = $request->all();
        $phone = $data['phone'];
        if(!preg_match('/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/',$phone)){
            return response()->json(['ERRORCODE' => 'ME10023', 'RESULT' => '请输入规范的手机号']);
        }
        $code = rand(100000,1000000);
        $request_data = [
            'phone'     => $phone,
            'appKey'    => config('app.dev_config')['appKey'],
            'params'    => $code.'|10',
            'tempID'    => 21109,
            'sendType'  => 2
        ];
        $url=config('app.request_url')['getVerifyCode'];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        if($body['ERRORCODE'] == "0"){
          $request->session()->put('verifyCode',$request_data['phone'].$code);
          $request->session()->put('expired_time',time()+600);
        }
        return response($result);
    }
    /**
    * 获取忘记密码的手机验证码
    * @param phone
    * @return code
    */
    public function resetPwdVerifyCode(Request $request){
        $data = $request->all();
        $phone = $data['phone'];
        if(!preg_match('/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/',$phone)){
            return response()->json(['ERRORCODE' => 'ME10023', 'RESULT' => '请输入规范的手机号']);
        }
        $request_data = [
            'mobile'    => $phone,
            'appKey'    => config('app.dev_config')['appKey']
        ];
        $url=config('app.request_url')['resetPwdCode'];
        $result = $this->common->accessApi($request_data,$url);
        return response($result);
    }

    

    /**
    * 修改用户的道客密码(忘记密码)
    * @param username daokePassword code
    * @return status
    */
    public function updateUserPwd(Request $request){
        $add_rules=[
            'username'  =>'required',
            'password'  =>'required',
            'rePassword'=>'required',
            'code'      =>'required',
        ];
        $post_data = $request->all();
        if(!isset($post_data['password']{5})){
            return response()->json(['ERRORCODE'=>'1005','RESULT'=>'密码长度不能小于六位']);
        }
        if($post_data['password'] != $post_data['rePassword']){
            return response()->json(['ERRORCODE'=>'1004','RESULT'=>'两次密码不一致']);
        }
        //获取错误信息
        $check_param=$this->checkParam($post_data,$add_rules);
        if($check_param->fails()){
            return response()->json(['ERRORCODE'=>'1000','RESULT'=>'参数验证错误']);
        }
        $request_data=[
            'mobile'      => $post_data['username'],
            'newPassword' => $post_data['password'],
            'verifyCode'  => $post_data['code'],
            'appKey'      => $post_data['appKey']
        ];
        $url=config('app.request_url')['resetPwd'];
        //访问接口,更新密码
        $result=$this->common->accessApi($request_data,$url);
        $body=json_decode($result,TRUE);
        unset($post_data['username']);
        unset($post_data['password']);
        unset($post_data['rePassword']);
        unset($post_data['code']);
        if($body['ERRORCODE']=='0'){
          $loginURL = $this->common->createLinkString(url('/oauth/login'),$post_data);
          Log::info('time: '.date('Y-m-d H:i:s',time()).' updateUserPwd  loginURL: '.$loginURL);
          return response()->json(['ERRORCODE'=>'0','RESULT'=>$loginURL]);
        }else{
            return response($result);
        }
    }



    /**
    * 验证码登录验证
    */

    // public function checkCodeLogin(Request $request){
    //     $add_rules = [
    //         'username'     => 'required|numeric',
    //         'code'         => 'required|numeric'
    //     ];
    //     $post_data    = $request->all();
    //     $check_param  = $this->checkParam($post_data,$add_rules);
    //     if($check_param->fails()){
    //         //参数验证错误 返回相应的登录页面
    //         return response()->json(['ERRORCODE' => '1000', 'RESULT' => '参数验证错误']);
    //     }
    //     $request_data = [
    //         'username'      => $post_data['username'],
    //         'daokePassword' => $post_data['code'],
    //         'appKey'        => $post_data['appKey']
    //     ];
    //     //请求 接口
    //     $url=config('app.request_url')['checkLogin'];
    //     $result = $this->common->accessApi($request_data,$url);
    //     $body = json_decode($result,TRUE);
    //     if($body['ERRORCODE'] == 'ME18061'){
    //         //账号不存在
    //         return response()->json(['ERRORCODE'=>$body['ERRORCODE'],'RESULT'=>$body['RESULT']]);
    //     }else{
    //         $session = $request->session()->all();
    //         if(!isset($session['verifyCode'])){
    //             return response()->json(['ERRORCODE'=>'2000','RESULT'=>'请先获取验证码']);
    //         }
    //         if(time() > $session['expired_time']){
    //             return response()->json(['ERRORCODE'=>'1003','RESULT'=>'验证码已过期']);
    //         }
    //         if($post_data['username'].$post_data['code'] != $session['verifyCode'] ){
    //             return response()->json(['ERRORCODE'=>'1001','RESULT'=>'您输入的验证码不正确']);
    //         }
    //         $request->session()->forget('verifyCode');
    //         $request->session()->forget('expired_time');
    //         $url = config('app.request_url')['getAccountIDFromMobile'];
    //         $request_data = [
    //             'mobile'    => $post_data['username'],
    //             'appKey'    => $post_data['appKey']
    //         ];
    //         $result = $this->common->accessApi($request_data,$url);
    //         $body = json_decode($result,TRUE);
    //         if($body['ERRORCODE'] != '0'){
    //             return response()->json(['ERRORCODE'=>$body['ERRORCODE'],'RESULT'=>$body['RESULT']]);
    //         }
    //         $data = [
    //             'accountID' => $body['RESULT']['0']['accountID'],
    //             'scope'     => 'userInfo',
    //             'appKey'    => $post_data['appKey']
    //         ];
    //         //调用接口
    //         $access_data=$this->common->getAccessToken($data);
    //         if($access_data['ERRORCODE']==0){
    //             $href_url = $this->common->createLinkString($post_data['redirect_url'],$access_data['RESULT']);
    //             Log::info('time: '.date('Y-m-d H:i:s',time()).' checkCodeLogin  href_url: '.$href_url);
    //             return response()->json(['ERRORCODE' => '0', 'RESULT' => $href_url]);
    //         }else{
    //             return response()->json(['ERRORCODE'=>$access_data['ERRORCODE'],'RESULT'=>$access_data['RESULT']]);
    //         }
    //     }
    // }

    /**
    * 修改登录密码
    */
    public function changePwd(Request $request){
        $add_rules=[
            'accountID'     =>'required',
            'oldPassword'   =>'required',
            'newPassword'   =>'required',
            'rePassword'    =>'required'
        ];
        $post_data = $request->all();
        if($post_data['newPassword'] != $post_data['rePassword']){
            return response()->json(['ERRORCODE'=>'1003','RESULT'=>'两次密码不一致']);
        }
        //获取错误信息
        $check_param=$this->checkParam($post_data,$add_rules);
        if($check_param->fails()){
            return response()->json(['ERRORCODE'=>'1000','RESULT'=>'参数验证错误']);
        }
        $request_data = [
            'appKey'        =>  $post_data['appKey'],
            'secret'        =>  config('app.dev_config')['secret'],
            'accountID'     =>  $post_data['accountID'],
            'oldPassword'   =>  $post_data['oldPassword'],
            'newPassword'   =>  $post_data['newPassword']
        ];
        $url=config('app.request_url')['updateUserPassword'];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        if($body['ERRORCODE'] == '0'){
            unset($post_data['accountID']);
            unset($post_data['oldPassword']);
            unset($post_data['newPassword']);
            unset($post_data['rePassword']);
            $loginURL = $this->common->createLinkString(url('/oauth/login'),$post_data);
            Log::info('time: '.date('Y-m-d H:i:s',time()).' changePwd  RESULT: '.$loginURL);
            return response()->json(['ERRORCODE'=>'0','RESULT'=>$loginURL]);
        }else{
            return response($result);
        }
        
    }

    /**
    * 修改手机号码
    */

    public function changeMobile(Request $request){
        $add_rules = [
            'accountID' =>  'required',
            'username'  =>  'required',
            'code'      =>  'required'
        ];
        $post_data = $request->all();
        $check_param = $this->checkParam($post_data,$add_rules);
        if($check_param->fails()){
            return response()->json(['ERRORCODE' => '1000', 'RESULT' => '参数验证错误']);
        }
        $session = $request->session()->all();
        if(!isset($session['verifyCode'])){
            return response()->json(['ERRORCODE'=>'2000','RESULT'=>'请先获取验证码']);
        }
        if(time() > $session['expired_time']){
            return response()->json(['ERRORCODE'=>'1003','RESULT'=>'验证码已过期']);
        }
        if($post_data['username'].$post_data['code'] != $session['verifyCode'] ){
            return response()->json(['ERRORCODE'=>'1001','RESULT'=>'您输入的验证码不正确']);
        }
        $request->session()->forget('key');
        $request->session()->forget('verifyCode');
        $url = config('app.request_url')['verifyEmailOrMobile'];
        $request_data = [
            'appKey'    =>  $post_data['appKey'],
            'mobile'    =>  $post_data['username'],
            'accountID' =>  $post_data['accountID']
        ];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        if($body['ERRORCODE'] == '0'){
            unset($post_data['accountID']);
            unset($post_data['username']);
            unset($post_data['code']);
            $loginURL = $this->common->createLinkString(url('/oauth/login'),$post_data);
            Log::info('time: '.date('Y-m-d H:i:s',time()).' changePhone  RESULT: '.$body['RESULT']);
            return response()->json(['ERRORCODE'=>'0','RESULT'=>$loginURL]);
        }else{
            return response($result);
        }
    }
}
