<?php
namespace App\Http\Controllers;
use Validator;
use QrCode;
use App\Console\Commands\common;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowController extends Controller {
    private $common;
    function __construct() {
        $this->common=new common();
    }
    /**
    * 登录页面
    * @param which display appKey redirect_url accountID
    */
    public function login(Request $request){
        // var_dump($_SERVER['HTTP_USER_AGENT']);exit;
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $get_data = $request->all();
        unset($get_data['_url']);
        $check_param = $this->checkParam($get_data);
        if($check_param->fails()){
            // if(strstr($which,'image/webp')){
            //     $get_data['display'] = 'pc';
            // }
            // $get_data['display'] = empty($get_data['display'])?'mobile':$get_data['display'];
            // return view($get_data['display'].'/404');
            return redirect('errors/404');
        }
        $login_data          = $get_data;
        $login_data['which'] = 'mobile';
        $login_data_code     = $get_data;
        $login_data_code['which'] = 'verifycode';
        $get_data['loginURL']       = $this->common->createLinkString(url('/oauth/login'),$login_data);
        $get_data['verifycodeURL']  = $this->common->createLinkString(url('/oauth/login'),$login_data_code);
        $get_data['registerURL']    = $this->common->createLinkString(url('/oauth/register'),$login_data);
        $get_data['forgetPwdURL']   = $this->common->createLinkString(url('/oauth/forgetPassword'),$login_data);
        $get_data['loginLogo']      = $this->getAppKeyInfo($get_data['appKey']);
        // if(strstr($which,'image/webp')){
        //     if($get_data['which'] == 'verifycode'){
        //         return response()->view('pc/login_'.$get_data['which'],['data' => $get_data]);
        //     }
        //     return response()->view('pc/login',['data' =>  $get_data]);
        // }
        if($get_data['which'] == 'verifycode'){
            return response()->view($login_data['display'].'/login_'.$get_data['which'],['data' => $get_data]);
        }
        return response()->view($login_data['display'].'/login',['data' => $get_data]);
    }

    
    /**
    * 注册页面
    * @param which display appKey redirect_url accountID
    */
    public function register(Request $request){
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $get_data = $request->all();
        unset($get_data['_url']);
        $check_param = $this->checkParam($get_data);
         //验证参数
        if($check_param->fails()){
            // if(strstr($which,'image/webp')){
            //     $get_data['display'] = 'pc';
            // }
            // $get_data['display'] = empty($get_data['display'])?'mobile':$get_data['display'];
            return view($get_data['display'].'/404');
        }
        $register_mobile    = $get_data;
        $register_email     = $get_data;
        $register_username  = $get_data;
        $register_succ      = $get_data;
        $register_mobile['which']    = 'mobile';
        $register_email['which']    = 'email';
        $register_username['which'] = 'username';
        $get_data['loginURL']       = $this->common->createLinkString(url('/oauth/login'),$get_data);
        $get_data['registerSucc']   = $this->common->createLinkString(url('/oauth/registerSucc'),$register_succ);
        $get_data['register_mobile']    = $this->common->createLinkString(url('/oauth/register'),$register_mobile);
        $get_data['register_email']     = $this->common->createLinkString(url('/oauth/register'),$register_email);
        $get_data['register_username']  = $this->common->createLinkString(url('/oauth/register'),$register_username);
        $get_data['loginLogo']          = $this->getAppKeyInfo($get_data['appKey']); 
        // if(strstr($which,'image/webp')){
        //     return response()->view('pc/register_'.$get_data['which'],['data' =>  $get_data]);
        // }
        return response()->view($get_data['display'].'/register_'.$get_data['which'],['data' => $get_data]);
    }
    

    /**
    * 加载忘记密码页面
    * @param which display appKey redirect_url 
    */
    public function forgetPassword(Request $request){
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $get_data = $request->all();
        unset($get_data['_url']);
        $check_param = $this->checkParam($get_data);
        if($check_param->fails()){
            // if(strstr($which,'image/webp')){
            //     $get_data['display'] = 'pc';
            // }
            // $get_data['display'] = empty($get_data['display'])?'mobile':$get_data['display'];
            return view($get_data['display'].'/404');
        }
        $get_data['loginLogo']      = $this->getAppKeyInfo($get_data['appKey']); 
        // if(strstr($which, 'image/webp')){
        //     return response()->view('pc/forget_pwd',['data' => $get_data]);
        // }
        return response()->view($get_data['display'].'/forget_pwd',['data' => $get_data]);
    }
    
    
    /**
    * 加载修改密码页面
    * @param which display appKey redirect_url accountID
    */
    public function changePassword(Request $request){
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $get_data = $request->all();
        unset($get_data['_url']);
        $add_rules = [
            'accountID' =>  'required'
        ];
        $check_param = $this->checkParam($get_data,$add_rules);
        if($check_param->fails()){
            // if(strstr($which,'image/webp')){
            //     $get_data['display'] = 'pc';
            // }
            // $get_data['display'] = empty($get_data['display'])?'mobile':$get_data['display'];
            return view($get_data['display'].'/404');
        }
        // if(strstr($which,'image/webp')){
        //     return response()->view('pc/change_pwd',['data' => $get_data]);
        // }
        $get_data['loginLogo']      = $this->getAppKeyInfo($get_data['appKey']); 
        return response()->view($get_data['display'].'/change_pwd',['data' => $get_data]);
    }


    /**
    * 加载更改手机页面
    * @param which display appKey redirect_url accountID
    */
    public function replaceMobile(Request $request){
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $add_rules = [
            'accountID' =>  'required'
        ];
        $get_data = $request->all();
        unset($get_data['_url']);
        $check_param = $this->checkParam($get_data,$add_rules);
        if($check_param->fails()){
            // if(strstr($which,'image/webp')){
            //     $get_data['display'] = 'pc';
            // }
            // $get_data['display'] = empty($get_data['display'])?'mobile':$get_data['display'];
            return view($get_data['display'].'/404');
        }
        $get_data['registerURL'] = $this->common->createLinkString(url('/oauth/register'),$get_data);
        $get_data['loginLogo']      = $this->getAppKeyInfo($get_data['appKey']); 
        // if(strstr($which,'image/webp')){
        //     return response()->view('pc/replace_phone',['data' => $get_data]);
        // }
        return response()->view($get_data['display'].'/replace_mobile',['data' => $get_data]);
    }

    /**
    * 加载登录成功页面
    */
    public function registerSucc(Request $request){
        // $which = $_SERVER['HTTP_ACCEPT'];//判断手机还是pc访问
        $get_data = $request->all();
        unset($get_data['_url']);
        $get_data['registerURL'] = $this->common->createLinkString(url('/oauth/register'),$get_data);
        $get_data['loginURL']    = $this->common->createLinkString(url('/oauth/login'),$get_data);
        $get_data['loginLogo']      = $this->getAppKeyInfo($get_data['appKey']); 
        // if(strstr($which, 'image/webp')){
        //     return response()->view('pc/register_succ',['data' => $get_data]);
        // }
        return response()->view($get_data['display'].'/register_succ',['data' => $get_data]);
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

    /*
    * 根据appKey 获取appLogo
    * @param appKey
    * @return string
    */
    public function getAppKeyInfo($appKey){
        $request_data = ['appKey'=>$appKey,'clientAppKey'=>$appKey];
        $url = config('app.request_url')['getAppKeyInfo'];
        $result = $this->common->accessApi($request_data,$url);
        $body = json_decode($result,TRUE);
        return $body['RESULT']['appLogo']; 
    }   
}
