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
    <div class="content-bg">
        <div class="content-one">
            <div><img src="{!! $data['loginLogo'] !!}" /></div>
            <section>
                <p>道客账号</p>
                <input class="username" type="text" placeholder="用户名(首位不能位数字)" />
            </section>
            <section>
                <p>密码</p>
                <input class="oldPsw" type="password" placeholder="最少六位" />
            </section>
            <section>
                <p>姓名</p>
                <input class="compellation" type="text" placeholder="中文" />
            </section>
            <button class="register-username-btn">注册</button>
            <em>
                <p>
                    <i><a href="{!! $data['register_mobile'] !!}">切换手机号注册</a></i>
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