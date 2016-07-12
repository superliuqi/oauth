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
    <div class="content-one">
        <div class="header-img">
            <img src="{!! $data['loginLogo'] !!}" />
        </div>
        <div class="input_base">
            <p>道客账号</p>
            <input class="username" type="text" placeholder="用户名(首位不能位数字)" />
        </div>
        <div class="input_base">
            <p>密码</p>
            <input class="oldPsw" type="password" placeholder="最少六位" />
        </div>
        <div class="input_base">
            <p>姓名</p>
            <input class="compellation" type="text" placeholder="中文" />
        </div>
        <button class="btn_sbm register-username-btn">注册</button>
        <div class="change-register-type">
            <a href="{!! $data['register_mobile'] !!}" class="link-left">切换手机号注册</a>
            <a href="{!! $data['register_email'] !!}" class="link-right">切换邮箱注册</a>
        </div>
        <div class="register-goto-login">
            <a href="{!! $data['loginURL'] !!}">已有账号登录</a>
        </div>
        <!-- 错误提示框 -->
        <div class="error-remind"></div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>
