<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-orientation" content="portrait">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {!! HTML::style('css/mobile/login_base.css') !!}
</head>
<body>
    @if (count($data) > 0)
        @foreach ($data as $k=>$v)
                <input type="hidden" name="{!!$k!!}" value="{!!$v!!}">
        @endforeach
    @endif
    <input type="hidden" class="login-psw-val" value="login" />
    <div class="content-bg">
        <div class="content-one">
            <div><img src="{!! $data['loginLogo'] !!}" /></div>
            <section class="login-psw-type">
                <p>用户名</p>
                <input class="username" type="text" placeholder="" />
            </section>
            <section>
                <p>密码</p>
                <input class="oldPsw" type="password" placeholder="密码" />
            </section>
            <button class="login-psw-btn">登录</button>
            <em>
                <p>
                    <i><a href="{!! $data['forgetPwdURL'] !!}">忘记密码</a></i>
                    <i></i>
                    <i><a href="{!! $data['verifycodeURL'] !!}">切换验证码登录</a></i>
                </p>
                <p><a href="{!! $data['registerURL'] !!}">还没有账号？点此去注册</a></p>
            </em>
            <div>
                <img src="http://www.daoke.me/img/daoke_logo.png" alt="道客" />
                <p>欢迎使用道客账号</p>
            </div>
        </div>
        <aside class="error-remind"></aside>
    </div>
    {!! HTML::script('js/mobile/zepto.min.js') !!}
    {!! HTML::script('js/mobile/login.js?t=160707') !!}
</body>
</html>