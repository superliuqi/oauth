<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title></title>
    {!! HTML::style('css/pc/reset.css') !!}
    {!! HTML::style('css/pc/pc_login_base.css') !!}
</head>
<body>
    @if (count($data) > 0)
        @foreach ($data as $k=>$v)
                <input type="hidden" name="{!!$k!!}" value="{!!$v!!}">
        @endforeach
    @endif
    <input type="hidden" class="check-security-type" value="1" />
    <div class="content-bg"></div>
    <div class="content-one">
        <div class="header-img">
            <img src="{!! $data['loginLogo'] !!}" />
        </div>
        <div class="input_base">
            <p>手机号码</p>
            <input class="phone" type="text" placeholder="手机号" />
        </div>
        <div class="input_base input-security">
            <p>验证码</p>
            <input class="security" type="text" placeholder="验证码" />
            <label class="security-get-btn">获取验证码</label>
            <label class="security-time-btn">60秒</label>
        </div>
        <div class="input_base">
            <p>密码</p>
            <input class="oldPsw" type="password" placeholder="至少六位" />
        </div>
        <div class="input_base">
            <p>姓名</p>
            <input class="compellation" type="text" placeholder="中文" />
        </div>
        <button class="btn_sbm register-btn">注册</button>
        <div class="change-register-type">
            <a href="{!! $data['register_email'] !!}" class="link-left">切换邮箱注册</a>
            <a href="{!! $data['register_username'] !!}" class="link-right">切换用户名注册</a>
        </div>
        <div class="register-goto-login">
            <a href="{!! $data['loginURL'] !!}">已有账号登录</a>
        </div>
        <!-- 错误提示框 -->
        <div class="error-remind"></div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>
