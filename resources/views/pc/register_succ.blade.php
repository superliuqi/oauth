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
    <div class="content-bg"></div>
    <div class="content-two">
        <div class="top_wrap">
            <img src="{!! $data['loginLogo'] !!}" />
            <span>道客账户</span>
            <b>账户注册</b>
        </div>
        <div class="mid_wrap">
            <img src="{{url()}}/images/register_succ_hook.png" alt="">
            <p>账户注册成功，<span class="succ-countdown"></span>秒后跳转登录</p>
        </div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>