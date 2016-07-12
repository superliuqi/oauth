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
                <p>旧密码</p>
                <input class="oldPsw" type="password" placeholder="旧密码" />
            </section>
            <section>
                <p>新密码</p>
                <input class="newPsw" type="password" placeholder="至少六位" />
            </section>
            <section>
                <p>重复新密码</p>
                <input class="rePsw" type="password" placeholder="重复新密码" />
            </section>
            <button class="change-psw-btn">提交</button>
            <button class="btn_back">返回</button>
        </div>
        <aside class="error-remind"></aside>
    </div>
    {!! HTML::script('js/mobile/zepto.min.js') !!}
    {!! HTML::script('js/mobile/login.js') !!}
</body>
</html>