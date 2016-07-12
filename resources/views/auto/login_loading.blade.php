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
	<div class="content-car-bg">
		<div class="content-car-two">
		    <header>
		    	<p><img src="{{url()}}/images/logo_01.png" />道客账户</p>
		    	<p></p>
		    	<p>登录系统</p>
		    </header>
		    <section>
			    <div>
			    	<img src="{{url()}}/images/loading.gif" alt="">
			    	<p>正在跳转道客账户登录系统，请稍等！</p>
			    </div>
		    </section>
		</div>
	</div>
	{!! HTML::script('js/auto/zepto.min.js') !!}
    {!! HTML::script('js/auto/login.js') !!}
</body>
</html>