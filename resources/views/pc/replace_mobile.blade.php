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
    <input type="hidden" class="check-security-type" value="1" />
    <div class="content-bg"></div>
    <div class="content-one">
        <div class="header-img">
            <img src="{!! $data['loginLogo'] !!}" />
        </div>
        <div class="input_base">
            <p>新手机号码</p>
            <input class="phone" type="text" placeholder="新手机号" />
        </div>
       <div class="input_base input-security">
            <p>验证码</p>
            <input class="security" type="text" placeholder="验证码" />
            <label class="security-get-btn">获取验证码</label>
            <label class="security-time-btn">60秒</label>
        </div>
        <button class="btn_sbm change-phone-btn">提交</button>
        <button class="btn_back">返回</button>
        <!-- 错误提示框 -->
        <div class="error-remind"></div>
    </div>
    {!! HTML::script('js/pc/jquery-1.12.1.min.js') !!}
    {!! HTML::script('js/pc/login.js') !!}
</body>
</html>
