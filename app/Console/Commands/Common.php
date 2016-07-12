<?php
namespace App\Console\Commands;
use Validator;
use Curl;
use Log;
use DateTime;
use PRedis;
class common{
    /**
    * 解析返回页面
    * @param which
    * @return views
    */
    public function returnViews($type='login',$which='all'){
        switch ($which) {
            case 'all':
                return $type;
                break;
            case 'username':
                return $type.'_username';
                break;
            case 'email':
                return $type.'_email';
                break;
            case 'phone':
                return $type.'_phone';
                break;
            case 'verifycode':
                return $type.'_verifycode';
                break;
            default:
                return 'errors/404';
                break;
        }
    }
    /**
    * sign
    * @param username password appKey secret
    * @return sign array
    */
    public function getSignArray($array){
        $array['secret'] = $this->getSecret($array['appKey']);
        foreach ($array as $key=>$value){
            $arr[$key] = $key;
        }
        sort($arr);
        $str = "";
        foreach ($arr as $k => $v){
            $str = $str.$arr[$k].$array[$v];
        }
        $array['sign'] = strtoupper(sha1($str));
        unset($array['secret']);
        return $array;
    }
    /**
    * check sign
    * @param array
    * @return  true false
    */
    public function checkSign($array){
      $p_sign = $array['sign'];
      unset($array['sign']);
      $array['secret'] = $this->getSecret($array['appKey']);
      foreach ($array as $key=>$value){
          $arr[$key] = $key;
      }
      sort($arr);
      $str = "";
      foreach ($arr as $k => $v){
          $str = $str.$arr[$k].$array[$v];
      }
      $sign = strtoupper(sha1($str));
      // print_r($sign);
      if($p_sign == $sign){
        return TRUE;
      }
      return FALSE;
    }
    /**
     * 生成回调请求参数
     * genju
     * @param $param
     * @return string
     */
    public function createLinkString($url='',$param) {
        $parse_array = parse_url($url);
        $query_str = http_build_query($param);
        if(isset($parse_array['query'])){
          $query_str .='&'.$parse_array['query'];
          $url = explode('?',$url)[0];
        }
        return $url.'?'.$query_str;
    }

    /**
     * 获取access_token
     * @param appKey sign code grantType accountID scope ....
     * @return param array
     */
    public function getAccessToken($request_data){
        $url=config('app.request_url')['getauthorizationCode'];
        $result = $this->accessApi($request_data,$url);
        $code = json_decode($result,TRUE);
        if($code['ERRORCODE']==0){
            $request_data['grantType']='authorizationCode';
            $request_data['code']=$code['RESULT']['authorizationCode'];
            $url=config('app.request_url')['getAccessToken'];
            $result = $this->accessApi($request_data,$url);
            $code = json_decode($result,TRUE);
            if(!isset($code['RESULT']['accountID'])){
                $code['RESULT']['accountID']=$request_data['accountID'];
            }
        }
        return $code;
    }
    /**
     * 更新access_token
     * @param appKey sign refreshToken grantType....
     * @return param array
     */
    public function updateAccessToken($request_data){
        $url=config('app.request_url')['updateAccessToken'];
        $result = $this->accessApi($request_data,$url);
        return json_decode($result,TRUE);
    }

    /**
     *  请求api
     *  @param array url
     *  @return param array
     */
    public function accessApi($request_data,$url){
      $time_start = microtime(true);
        $result = Curl::to($url)
            ->withData($this->getSignArray($request_data))->post();
      $time_end = microtime(true);
      Log::info("request ".$url." consume".($time_end - $time_start)."secondes".' request_data '.json_encode($request_data).' return '.$result);
      return $result;
    }
    /**
    * 通过缓存获取 secret
    * @param appKey
    * @return secret
    */
    public function getSecret($appKey){
      if($appKey == config('app.dev_config')['appKey']){
        return config('app.dev_config')['secret'];
      }
      return PRedis::hget($appKey.':appKeyInfo','secret');
    }
    /**
    * 星号隐藏字符
    * @param IDNumber name
    * @return  str
    */
    public function replaceStr($param){
      if(is_numeric($param)){
        return preg_replace('/(\d{5})\d{8}(\d{5})/', '$1********$2', $param);
      }
      return mb_substr($param, 0, 1, 'UTF-8') . '*' . mb_substr($param, -1, mb_strlen($param,'UTF-8')-2, 'UTF-8');
    }
    /**
     * 身份证号码验证
     * @param IDNumber
     * @return bool
    */
    public function checkIDNumber($IDNumber){
        if(empty($IDNumber)){
          return false;
        }
        $city_array = [
            11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",
            22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",
            35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",
            44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",
            53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",
            65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外"
        ];
        $iSum = 0;
        $IDNumberLength = strlen($IDNumber);
       //长度验证
        if(!preg_match('/^\d{17}(\d|x)$/i',$IDNumber) and!preg_match('/^\d{15}$/i',$IDNumber)){
          return false;
        }
       //地区验证
        if(!array_key_exists(intval(substr($IDNumber,0,2)),$city_array)){
            return false;
          }
       // 15位身份证验证生日，转换为18位
        if ($IDNumberLength == 15){
          $sBirthday = '19'.substr($IDNumber,6,2).'-'.substr($IDNumber,8,2).'-'.substr($IDNumber,10,2);
          $d = new DateTime($sBirthday);
          $dd = $d->format('Y-m-d');
          if($sBirthday != $dd){
            return false;
          }
          $IDNumber = substr($IDNumber,0,6)."19".substr($IDNumber,6,9);//15to18
          $Bit18 = getVerifyBit($IDNumber);//算出第18位校验码
          $IDNumber = $IDNumber.$Bit18;
        }
          // 判断是否大于2078年，小于1900年
          $year = substr($IDNumber,6,4);
          if ($year<1900 || $year>2078 ){
           return false;
          }
          //18位身份证处理
          $sBirthday = substr($IDNumber,6,4).'-'.substr($IDNumber,10,2).'-'.substr($IDNumber,12,2);
          $d = new DateTime($sBirthday);
          $dd = $d->format('Y-m-d');
          if($sBirthday != $dd){
           return false;
          }
         //身份证编码规范验证
         $IDNumber_base = substr($IDNumber,0,17);
         if(strtoupper(substr($IDNumber,17,1)) != $this->getVerifyBit($IDNumber_base)){
           return false;
         }else{
           return true;
         }
      }

      // 计算身份证校验码，根据国家标准GB 11643-1999
      function getVerifyBit($IDNumber_base){
       if(strlen($IDNumber_base) != 17){
        return false;
       }
       //加权因子
       $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
       //校验码对应值
       $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
       $checksum = 0;
       for ($i = 0; $i < strlen($IDNumber_base); $i++)
       {
        $checksum += substr($IDNumber_base, $i, 1) * $factor[$i];
       }
       $mod = $checksum % 11;
       $verify_number = $verify_number_list[$mod];
       return $verify_number;
      }
}
