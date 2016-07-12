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
    <input type="hidden" class="login-psw-val" value="login" />
    <div class="content-bg"></div>
    <div class="content-one">
        <div class="header-img">
            <img src="{!! $data['loginLogo'] !!}" />
        </div>
        <div class="input_base login-psw-type">
            <p>用户名</p>
            <input class="username" type="text" placeholder="" />
        </div>
        <div class="input_base">
            <p>密码</p>
            <input class="oldPsw" type="password" placeholder="密码" />
        </div>
        <button class="btn_sbm login-psw-btn">登录</button>
        <div>
            <p class="content-links">
                <a href="{!! $data['forgetPwdURL'] !!}" class="link-forget-psw">忘记密码</a>
                <a href="{!! $data['verifycodeURL'] !!}" class="link-login-security">切换验证码登录</a>
            </p>
            <p class="link-go-register"><a href="{!! $data['registerURL'] !!}">还没有账号？点此去注册</a></p>
        </div>
        <div class="btm-content">
            <img src="http://www.daoke.me/img/daoke_logo.png" alt="">
            <p>欢迎使用道客账号</p>
        </div>
        <!-- 错误提示框 -->
        <div class="error-remind"></div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js?t=160707') !!}
</body>
</html>
