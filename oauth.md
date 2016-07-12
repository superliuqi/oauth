## 上海语镜道客账号第三方授权
### 说明文档
#### 1、道客账号登录
> 请求地址
>
> http://oauth.daoke.me/oauth/login
>
> 请求方式
> GET
>
> 参考链接
>
> http://oauth.daoke.me/oauth/login?which=phone&display=mobile&appKey=123&redirect_url=http%3A%2F%2Fdaoke.me

参数说明

| 参数         | 是否必须       | 说明                                                                      |
| -------------|:-------------:| -------------------------------------------------------------------------:|
| which        | 是            | 使用什么登录方式 phone:手机号,username:用户名,verifycode:手机验证码, email:邮箱|
| display      | 是            | 展示方式 mobile:移动端,pc:电脑                                              |
| appKey       | 是            | 应用appKey                                                                 |
| redirect_url | 是            | 登录成功后重定向的回调链接地址,请使用urlencode对链接进行处理,仅可拼接一个get方式传递的参数                   |

返回参数说明

| 参数                      | 说明                   |
| --------------------------|:---------------------:|
| accessToken              |  网页授权接口调用凭证   |
| accountID                 |  道客唯一编号 accountID|
| accessTokenExpiration     |  接口调用凭证有效期     |
| refreshToken              |  更新接口调用凭证       |
| refreshTokenExpiration    |  更新接口调用凭证有效期 |


##### 2、道客账号注册
> 请求地址
>
> http://oauth.daoke.me/oauth/register
>
> 请求方式
> GET
>
> 参考链接
>
> http://oauth.daoke.me/oauth/register?which=phone&display=mobile&appKey=123&redirect_url=http%3A%2F%2Fdaoke.me

参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| which        | 是            | 使用什么注册方式 phone:手机号,username:用户名, email:邮箱|
| display      | 是            | 展示方式 mobile:移动端,pc:电脑                                             |
| appKey       | 是            | 应用appKey                                                              |
| redirect_url | 是            | 登录成功后重定向的回调链接地址,请使用urlencode对链接进行处理,仅可拼接一个get方式传递的参数   |


返回参数说明

| 参数                      | 说明                   |
| --------------------------|:---------------------:|
| accessToken              |  网页授权接口调用凭证   |
| accountID                 |  道客唯一编号 accountID|
| accessTokenExpiration     |  接口调用凭证有效期     |
| refreshToken              |  更新接口调用凭证       |
| refreshTokenExpiration    |  更新接口调用凭证有效期 |


##### 3、道客账号忘记密码
> 请求地址
>
> http://oauth.daoke.me/oauth/forgetPassword
>
> 请求方式
> GET
>
> 参考链接
>
> http://oauth.daoke.me/oauth/forgetPassword?which=phone&display=mobile&appKey=123&redirect_url=http%3A%2F%2Fdaoke.me

参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| which        | 是            | 使用什么找回密码方式 phone:手机号, email:邮箱                                |
| display      | 是            | 展示方式 mobile:移动端,pc:电脑                                             |
| appKey       | 是            | 应用appKey                                                              |
| redirect_url | 是            | 修改成功后重定向的回调链接地址,请使用urlencode对链接进行处理,仅可拼接一个get方式传递的参数   |

##### 4、获取二维码-扫码登录
> 请求地址
>
> http://oauth.daoke.me/oauth/getQR
>
> 请求方式
> GET
>
> 参考链接
>
> http://oauth.daoke.me/oauth/getQR?accountID=xxxx&appKey=ccc&redirect_url=http%3A%2F%2Fdaoke.me&access_token=12312
>
参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| accountID    | 是            | 道客编号                                    |
| access_token | 是            | 网页授权接口调用凭证                          |
| appKey       | 是            | 应用appKey                                 |
| redirect_url | 是            | 扫码登录成功后跳转的地址,仅可拼接一个get方式传递的参数     |

返回参数说明
>二维码文件的二进制流

##### 5、验证iccid 是否实名认证
> 请求地址
>
> http://oauth.daoke.me/oauth/isVerifiedIccid
>
> 请求方式
> POST
>
参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| iccid        | 是            | IC卡唯一识别号                               |
| access_token | 否            | 暂不支持该参数                          |
| appKey       | 是            | 应用appKey                                 |
| sign         | 是            | 签名     |

返回参数说明
>
JSON返回示例：

 > **失败示例**
 ```
 {
       "ERRORCODE":"ME10002",
       "RESULT":"传入参数错误"
   }
 ```
 > **成功示例**
 ```
 {
     "ERRORCODE":"0",
     "RESULT":{
             "accountID":"1"  // 用户唯一编号,
             "verifyStatus":"0",       // 认证状态 0:正在审核,1:已通过审核,2:认证失败
             "iccid":""    // 卡唯一识别码
         }
     }
 ```
 >     

##### 6、实名认证接口
 > 请求地址
 >
 > http://oauth.daoke.me/oauth/toVerifyName
 >
 > 请求方式
 > POST
 >
 参数说明

 | 参数        | 是否必须           | 说明  |
 | -------------|:-------------:| -------:|
 | iccid        | 是            | IC卡唯一识别号                               |
 | imei         | 是            | 设备识别码                               |
 | accountID    | 是            | 道客用户唯一编号                              |
 | IDNumber     | 是            | 道客用户身份证号                              |
 | name          | 是            | 道客用户姓名                              |
 | gender     | 是            | 道客用户性别                             |
 | IDCardPosiUrl| 是            | 道客用户身份证正面照片URL                      |
 | IDCardNegaUrl| 是            | 道客用户身份证背面照片URL                      |
 | access_token | 否            | 暂不支持该参数                                |
 | appKey       | 是            | 应用appKey                                  |
 | sign         | 是            | 签名     |

 返回参数说明
 >
 JSON返回示例：

  > **失败示例**
  ```
  {
        "ERRORCODE":"ME10002",
        "RESULT":"传入参数错误"
    }
  ```
  > **成功示例**
  ```
  {
      "ERRORCODE":"0",
      "RESULT":"ok"
      }
 ```

##### 7、通过imsi 查询ICCID
> 请求地址
>
> http://oauth.daoke.me/oauth/getIccidByImsi
>
> 请求方式
> POST
>
参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| imsi        | 是            | IC卡唯一识别号                               |
| appKey       | 是            | 应用appKey                                  |
| sign         | 是            | 签名     |

返回参数说明
>
JSON返回示例：

 > **失败示例**
 ```
 {
       "ERRORCODE":"ME10002",
       "RESULT":"传入参数错误"
   }
 ```
 > **成功示例**
 ```
 {
     "ERRORCODE":"0",
     "RESULT":{
        "imsi":"",
        "iccid":"",
        "simStatus":""
   }
     }
```
##### 8、检查accountID是否实名,和实名的次数
> 请求地址
>
> http://oauth.daoke.me/oauth/isVerifiedAccount
>
> 请求方式
> POST
>
参数说明

| 参数        | 是否必须           | 说明  |
| -------------|:-------------:| -------:|
| accountID        | 是            | IC卡唯一识别号                               |
| access_token | 否            | 暂不支持该参数                                |
| appKey       | 是            | 应用appKey                                  |
| sign         | 是            | 签名     |

返回参数说明
>
JSON返回示例：

 > **失败示例**
 ```
 {
       "ERRORCODE":"ME10006",
       "RESULT":"verify is failed "
   }
 ```
 > **成功示例**
 ```
 {
     "ERRORCODE":"0",
     "RESULT":{
        "name":"张*",
        "IDNumber":"3400******0909",
        "gender":"1"
   }
     }
```
##### 9、补全认证信息接口
 > 请求地址
 >
 > http://oauth.daoke.me/oauth/completeRealName
 >
 > 请求方式
 > POST
 >
 参数说明

 | 参数        | 是否必须           | 说明  |
 | -------------|:-------------:| -------:|
 | iccid        | 是            | IC卡唯一识别号                               |
 | imei         | 是            | 设备识别码                               |
 | accountID    | 是            | 道客用户唯一编号                              |
 | IDNumber     | 是            | 道客用户身份证号                              |
 | name          | 是            | 道客用户姓名                              |
 | gender     | 是            | 道客用户性别                             |
 | access_token | 否            | 暂不支持该参数                                |
 | appKey       | 是            | 应用appKey                                  |
 | sign         | 是            | 签名     |

 返回参数说明
 >
 JSON返回示例：

  > **失败示例**
  ```
  {
        "ERRORCODE":"ME10002",
        "RESULT":"传入参数错误"
    }
  ```
  > **成功示例**
  ```
  {
      "ERRORCODE":"0",
      "RESULT":"ok"
      }
 ```
