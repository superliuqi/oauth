<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/oauth/toVerifyName',
        '/oauth/isVerifiedIccid',
        '/oauth/getIccidByImsi',
        '/oauth/isVerifiedAccount',
        '/oauth/completeRealName',
        '/oauth/checkLogin',
        '/oauth/getVerifyCode',
        '/oauth/changePwd',
        '/oauth/checkCodeLogin',
        '/oauth/registerUser',
        '/oauth/resetPwdVerifyCode',
        '/oauth/changeMobile',
        '/oauth/getTestSign',
        '/oauth/changePwd',
    ];
}
