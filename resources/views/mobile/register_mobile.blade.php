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
    <input type="hidden" class="check-security-type" value="1" />
    <div class="content-bg">
        <div class="content-one">
            <div><img src="{!! $data['loginLogo'] !!}" /></div>
            <section>
                <p>手机号码</p>
                <input class="phone" type="text" placeholder="手机号" />
            </section>
            <section class="input-security">
                <p>验证码</p>
                <input class="security" type="text" placeholder="验证码" />
                <label class="security-get-btn">获取验证码</label>
                <label class="security-time-btn">60秒</label>
            </section>
            <section>
                <p>密码</p>
                <input class="oldPsw" type="password" placeholder="至少六位" />
            </section>
            <section>
                <p>姓名</p>
                <input class="compellation" type="text" placeholder="中文" />
            </section>
            <button class="register-btn">注册</button>
            <em>
                <p>
                    <i><a href="{!! $data['register_username'] !!}">切换用户名注册</a></i>
                    <i></i>
                    <i><a href="{!! $data['register_email'] !!}">切换邮箱注册</a></i>
                </p>
                <p><a href="{!! $data['loginURL'] !!}">已有账号登录</a></p>
            </em>
        </div>
        <aside class="error-remind"></aside>
    </div>
    {!! HTML::script('js/mobile/zepto.min.js') !!}
    {!! HTML::script('js/mobile/login.js') !!}
</body>
</html>