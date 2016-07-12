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
    <input type="hidden" class="check-security-type" value="3" />
    <div class="content-bg">
        <div class="content-one">
            <div><img src="{!! $data['loginLogo'] !!}" /></div>
            <section>
                <p>手机号码</p>
                <input class="phone" type="text" placeholder="手机号码" />
            </section>
            <section class="input-security">
                <p>验证码</p>
                <input class="security" type="text" placeholder="验证码" />
                <label class="security-get-btn">获取验证码</label>
                <label class="security-time-btn">60秒</label>
            </section>
            <button class="login-security-btn">登录</button>
            <em>
                <p>
                    <i></i>
                    <i></i>
                    <i><a href="{!! $data['loginURL'] !!}">切换成账号登录</a></i>
                </p>
            </em>
            <div>
                <img src="http://www.daoke.me/img/daoke_logo.png" alt="道客" />
                <p>欢迎使用道客账号</p>
            </div>
        </div>
        <aside class="error-remind"></aside>
    </div>
    {!! HTML::script('js/mobile/zepto.min.js') !!}
    {!! HTML::script('js/mobile/login.js') !!}
</body>
</html>