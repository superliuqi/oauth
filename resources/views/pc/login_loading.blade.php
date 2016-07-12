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
            <img src="{{url()}}/images/logo_01.png" />
            <span>道客账户</span>
            <b><a href="{!! $data['loginURL'] !!}">账户登录</a></b>
        </div>
        <div class="mid_wrap">
            <img src="{{url()}}/images/loading.gif" alt="">
            <p>正在跳转道客账户登录系统，请稍等！</p>
        </div>
	</div>
</body>
</html>