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
            <p>旧密码</p>
            <input class="oldPsw" type="password" placeholder="旧密码" />
        </div>
        <div class="input_base">
            <p>新密码</p>
            <input class="newPsw" type="password" placeholder="至少六位" />
        </div>
        <div class="input_base">
            <p>重复新密码</p>
            <input class="rePsw" type="password" placeholder="重复新密码" />
        </div>
        <button class="btn_sbm change-psw-btn">提交</button>
        <button class="btn_back">返回</button>
        <!-- 错误提示框 -->
        <div class="error-remind"></div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>