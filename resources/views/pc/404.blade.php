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
    <div class="content-bg"></div>
    <div class="content-two">
        <div class="top_wrap">
            <img src="{!! $data['loginLogo'] !!}" />
            <span>道客账户</span>
            <!-- <b>账户登录</b> -->
        </div>
        <div class="mid_content">
            <img src="http://www.daoke.me/img/daoke_logo.png" alt="">
            <p>未连接到道客账户登录系统，参数错误请重试!</p>
            <button class="btn_back">返回</button>
        </div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>