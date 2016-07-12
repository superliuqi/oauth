$(function(){
    //返回上一页
    $('.btn_back').on('tap', function() {
        history.go(-1);
    });

    // 错误信息提示弹框
    function showTips(content) {
        $('.error-remind').css('display', 'block').html(content).addClass('twink');
        $('.error-remind').on("webkitAnimationEnd", function() { //动画结束时事件
            $('.error-remind').removeClass('twink').css('display', 'none');
        });
    }

    // 密码正则
    var pswReg = /^\w{6,18}$/;
    //手机号正则
    var phoneReg = /^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/;
    //邮箱正则
    var emailReg = /^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/;
    // 验证码
    var securityReg = /^[\d]{6}$/;
    //用户名
    var usernameReg = /^[a-zA-Z][\w\d]{5,19}$/;
    // 姓名
    var compellationReg = /^[\u4e00-\u9fa5]+$/;

    //获取验证码+倒计时
    $('.security-get-btn').on('tap', function() {
        if ($('.check-security-type').val() == 1) { //手机注册、修改手机
            var securityUrl = "/oauth/getVerifyCode";
        } else if ($('.check-security-type').val() == 2) { //忘记密码
            var securityUrl = "/oauth/resetPwdVerifyCode";
        } else if ($('.check-security-type').val() == 3) { //验证码登录
            var securityUrl = "/oauth/getDynamicPwd";
        }
        
        if (!phoneReg.test($('.phone').val())) {
            showTips('您输入的手机号有误');
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "post",
                url: securityUrl,
                data: {
                    which: $('input[name="which"]').val(),
                    display: $('input[name="display"]').val(),
                    appKey: $('input[name="appKey"]').val(),
                    redirect_url: $('input[name="redirect_url"]').val(),
                    phone: $('.phone').val()
                },
                dataType:"json",
                success: function(data) {
                    // console.log(data)
                    if (data.ERRORCODE == '0') {
                        $('.security-get-btn').css('display', 'none');
                        $('.security-time-btn').css('display', 'block');
                        var num = 60;
                        var securityTime = setInterval(function() {
                            num--;
                            $('.security-time-btn').html(num + '秒');
                            if (num == 0) {
                                clearInterval(securityTime);
                                $('.security-get-btn').css('display', 'block');
                                $('.security-time-btn').css('display', 'none').html('60秒');
                            }
                        }, 1000)
                    }else if(data.ERRORCODE == 'ME18927'){
                        showTips('操作太频繁');
                        return false;
                    }else if(data.ERRORCODE == 'ME18909'){
                        showTips('验证码发送失败稍后再试');
                        return false;
                    }else{
                        showTips('系统有误请稍后再试');
                        return false;
                    }
                }
            });
        }
    });

    //修改密码
    function toChangePsw() {
        var oldPsw = $('.oldPsw').val();
        var newPsw = $('.newPsw').val();
        var rePsw = $('.rePsw').val();

        if (oldPsw == '' || !pswReg.test(oldPsw)) {
            showTips('请输入道客密码');
            return false;
        }
        if (newPsw == '' || !pswReg.test(newPsw)) {
            showTips('请输入至少6位密码');
            return false;
        }
        if (rePsw == '' || rePsw != newPsw) {
            showTips('两次密码输入不一致');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'post',
            url: '/oauth/changePwd',
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                accountID: $('input[name="accountID"]').val(),
                // accountID:'P5UKkGCbWV',//测试用
                oldPassword: oldPsw,
                newPassword: newPsw,
                rePassword: rePsw,
            },
            dataType:"json",
            success: function(data) {
                // console.log(data)
                if(data.ERRORCODE==0){
                    // console.log(data.RESULT)
                    location.href = data.RESULT;
                }else{
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.change-psw-btn').on('tap', function() {
        toChangePsw();
    })

    // 忘记密码
    function toForgetPsw() {
        var phone = $('.phone').val();
        var security = $('.security').val();
        var newPsw = $('.newPsw').val();
        var rePsw = $('.rePsw').val();

        if (phone == '' || !phoneReg.test(phone)) {
            showTips('请输入正确的手机号');
            return false;
        }
        if (security == '' || !securityReg.test(security)) {
            showTips('请输入正确的验证码');
            return false;
        }
        if (newPsw == '' || !pswReg.test(newPsw)) {
            showTips('请输入至少6位密码');
            return false;
        }
        if (rePsw == '' || rePsw != newPsw) {
            showTips('两次密码输入不一致');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/updateUserPwd",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                username: phone,
                password: newPsw,
                rePassword: rePsw,
                code: security
            },
            dataType:"json",
            success: function(data) {
                // console.log(data)
                if (data.ERRORCODE == 0) {
                    location.href = data.RESULT;
                } else if(data.ERRORCODE =='ME18061') {
                    showTips('手机号未注册');
                    return false;
                } else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })
        return true;
    }
    $('.forget-psw-btn').on('tap', function() {
        toForgetPsw();
    });

    // 密码登录判断 手机登录/用户名登录/邮箱登录
    if ($('.login-psw-val').val() == 'login') {
        if($('input[name="which"]').val() == 'mobile'){
            $('.login-psw-type p').html('手机号');
            $('.login-psw-type input').attr('placeholder','手机号');
        }else if($('input[name="which"]').val() == 'username'){
            $('.login-psw-type p').html('用户名');
            $('.login-psw-type input').attr('placeholder','用户名');
        }else if($('input[name="which"]').val() == 'email'){
            $('.login-psw-type p').html('邮箱');
            $('.login-psw-type input').attr('placeholder','邮箱');
        }
        
    }

    //密码登录
    function toPswLogin() {
        var username = $('.username').val();
        var oldPsw = $('.oldPsw').val();

        if($('input[name="which"]').val() == 'mobile'){
            var loginReg = phoneReg;
        }else if($('input[name="which"]').val() == 'username'){
            var loginReg = usernameReg;
        }else if($('input[name="which"]').val() == 'email'){
            var loginReg = emailReg;
        }
        // console.log('loginReg:'+usernameReg)

        if (username == '' || (!loginReg.test(username))) {
            showTips('请输入规范的道客账号');
            return false;
        }
        if (oldPsw == '' || !pswReg.test(oldPsw)) {
            showTips('请输入道客密码');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/checkLogin",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                username: username,
                password: oldPsw
            },
            dataType:"json",
            success: function(data) {
                if (data.ERRORCODE == 0) {
                    // console.log(data)
                    // console.log(data.RESULT)
                    location.href = data.RESULT;
                } else {
                    showTips('您的账号或密码输入有误');
                    return false;
                }
            }
        })

        return true;
    }
    $('.login-psw-btn').on('tap', function() {
        toPswLogin();
    });

    //验证码登录
    function toSecurityLogin() {
        var phone = $('.phone').val();
        var security = $('.security').val();

        if (phone == '' || !phoneReg.test(phone)) {
            showTips('请输入正确的手机号');
            return false;
        }
        if (security == '' || !securityReg.test(security)) {
            showTips('请输入正确的验证码');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/checkDynamicPwd",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                phone: phone,
                code: security
            },
            dataType:"json",
            success: function(data) {
                // console.log(data)
                if (data.ERRORCODE == 0) {
                    location.href = data.RESULT;
                } else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.login-security-btn').on('tap', function() { //验证码登录
        toSecurityLogin();
    });

    // 更换手机号
    function toChangePhone() {
        var phone = $('.phone').val();
        var security = $('.security').val();

        if (phone == '' || !phoneReg.test(phone)) {
            showTips('请输入正确的手机号');
            return false;
        }
        if (security == '' || !securityReg.test(security)) {
            showTips('请输入正确的验证码');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/changeMobile",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                accountID: $('input[name="accountID"]').val(),
                username: phone,
                code: security
            },
            dataType:"json",
            success: function(data) {
                // console.log(data)
                if (data.ERRORCODE == 0) {
                    // console.log(data.RESULT)
                    location.href = data.RESULT;
                } else if(data.ERRORCODE == '2000') {
                    showTips('请重新获取验证码');
                    return false;
                } else if(data.ERRORCODE == 'ME18916'){
                    showTips('此手机号已被注册');
                    return false;
                }else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.change-phone-btn').on('tap', function() { //更换手机
        toChangePhone();
    });

    // 注册 手机号
    function toRegister() {
        var phone = $('.phone').val();
        var security = $('.security').val();
        var oldPsw = $('.oldPsw').val();
        var compellation = $('.compellation').val();

        if (phone == '' || !phoneReg.test(phone)) {
            showTips('请输入正确的手机号');
            return false;
        }
        if (security == '' || !securityReg.test(security)) {
            showTips('请输入正确的验证码');
            return false;
        }
        if (oldPsw == '' || !pswReg.test(oldPsw)) {
            showTips('请输入道客密码');
            return false;
        }
        if (compellation == '' || !compellationReg.test(compellation)) {
            showTips('请输入中文姓名');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/registerUser",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                username: phone,
                password: oldPsw,
                code: security,
                nickname: compellation
            },
            dataType:"json",
            success: function(data) {
                // console.log(data)
                if (data.ERRORCODE == 0) {
                    // location.href = $('input[name="registerSucc"]').val();
                    location.href = data.RESULT;
                } else if (data.ERRORCODE == 'ME18002') {
                    showTips('手机号已被注册');
                    return false;
                }else if(data.ERRORCODE == '2000') {
                    showTips('请重新获取验证码');
                    return false;
                } else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.register-btn').on('tap', function() {
        toRegister();
    });

    //注册 邮箱
    function toRegisterEmail() {
        var email = $('.email').val();
        var oldPsw = $('.oldPsw').val();
        var compellation = $('.compellation').val();

        if (email == '' || !emailReg.test(email)) {
            showTips('请输入正确的邮箱');
            return false;
        }
        if (oldPsw == '' || !pswReg.test(oldPsw)) {
            showTips('请输入道客密码');
            return false;
        }
        if (compellation == '' || !compellationReg.test(compellation)) {
            showTips('请输入中文姓名');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/registerUser",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                username: email,
                password: oldPsw,
                nickname: compellation
            },
            dataType:"json",
            success: function(data) {
                if (data.ERRORCODE == 0) {
                    // location.href = $('input[name="registerSucc"]').val();
                    location.href = data.RESULT;
                } else if (data.ERRORCODE == 'ME18002') {
                    showTips('邮箱已被注册');
                    return false;
                } else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.register-email-btn').on('tap', function() {
        toRegisterEmail();
    });

    //注册 道客账号
    function toRegisterUsername() {
        var username = $('.username').val();
        var oldPsw = $('.oldPsw').val();
        var compellation = $('.compellation').val();

        if (username == '' || !usernameReg.test(username)) {
            showTips('请输入正确的道客账号');
            return false;
        }
        if (oldPsw == '' || !pswReg.test(oldPsw)) {
            showTips('请输入道客密码');
            return false;
        }
        if (compellation == '' || !compellationReg.test(compellation)) {
            showTips('请输入中文姓名');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "post",
            url: "/oauth/registerUser",
            data: {
                which: $('input[name="which"]').val(),
                display: $('input[name="display"]').val(),
                appKey: $('input[name="appKey"]').val(),
                redirect_url: $('input[name="redirect_url"]').val(),
                username: username,
                password: oldPsw,
                nickname: compellation
            },
            dataType:"json",
            success: function(data) {
                if (data.ERRORCODE == 0) {
                    // location.href = $('input[name="registerSucc"]').val();
                    location.href = data.RESULT;
                } else if (data.ERRORCODE == 'ME18002') {
                    showTips('用户名已被注册');
                    return false;
                } else {
                    showTips('系统有误请稍后再试');
                    return false;
                }
            }
        })

        return true;
    }
    $('.register-username-btn').on('tap', function() {
        toRegisterUsername();
    });

    // 注册成功 跳转倒计时
    // function countdown(num) {
    //     if ($('.succ-countdown').length == 1) {
    //         $('.succ-countdown').html(num);
    //         var countdownTime = setInterval(function() {
    //             num--;
    //             $('.succ-countdown').html(num);
    //             if (num == 0) {
    //                 clearInterval(countdownTime);
    //                 //跳转登录页面
    //                 location.href = $('input[name="loginURL"]').val();
    //             }
    //         }, 1000)
    //     }
    // }
    // countdown(3);

    //回车键 触发提交
    document.onkeydown = function(e) {
        if (event.keyCode == 13) {
            $('input').blur();//失去焦点

            if($('.change-psw-btn').length==1){//修改密码
                toChangePsw();
            }
            if($('.forget-psw-btn').length==1){// 忘记密码
                toForgetPsw();
            }
            if($('.login-psw-btn').length==1){//密码登录
                toPswLogin();
            }
            if($('.login-security-btn').length==1){//验证码登录
                toSecurityLogin();
            }
            if($('.change-phone-btn').length==1){//更换手机
                toChangePhone();
            }
            if($('.register-btn').length==1){//手机注册
                toRegister();
            }
            if($('.register-email-btn').length==1){//邮箱注册
                toRegisterEmail();
            }
            if($('.register-username-btn').length==1){//账号注册
                toRegisterUsername();
            }
        }
    }
})