<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {!! HTML::style('css/auto/car_login_base.css') !!}
</head>
<body>
    @if (count($data) > 0)
        @foreach ($data as $k=>$v)
                <input type="hidden" name="{!!$k!!}" value="{!!$v!!}">
        @endforeach
    @endif
    <div class="content-car-bg">
        <div class="content-car-one">
            <div>
                <b>
                    <img src="{!! $data['loginLogo'] !!}" />
                    <p><a class="btn_back">返回</a></p>
                </b>
            </div>
            <div>
                <b>
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
                </b>
            </div>
        </div>
        <aside class="error-remind"></aside>
    </div>
    {!! HTML::script('js/auto/zepto.min.js') !!}
    {!! HTML::script('js/auto/login.js') !!}
</body>
</html>
