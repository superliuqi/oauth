<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', function (Request $request) {
  return view('welcome');});

Route::get('/oauth/login', 'ShowController@login');
Route::get('/oauth/register', 'ShowController@register');
Route::get('/oauth/forgetPassword', 'ShowController@forgetPassword');
Route::get('/oauth/getQR', 'OauthController@createQR');
Route::get('/oauth/checkQR','OauthController@checkQR');
Route::get('/oauth/changePassword','ShowController@changePassword');
Route::get('/oauth/replaceMobile','ShowController@replaceMobile');
Route::get('/oauth/registerSucc','ShowController@registerSucc');

Route::post('/oauth/checkLogin','OauthController@checkLogin');
Route::post('/oauth/changeMobile','OauthController@changeMobile');
Route::post('/oauth/checkCodeLogin','OauthController@checkCodeLogin');
Route::post('/oauth/checkRegister','OauthController@checkRegister');
Route::post('/oauth/getDynamicPwd','OauthController@getDynamicPwd');
Route::post('/oauth/checkDynamicPwd','OauthController@checkDynamicPwd');
Route::post('/oauth/resetPwdVerifyCode','OauthController@resetPwdVerifyCode');
Route::post('/oauth/getVerifyCode','OauthController@getVerifyCode');
Route::post('/oauth/registerUser','OauthController@registerUser');
Route::post('/oauth/updateUserPwd','OauthController@updateUserPwd');
Route::post('/oauth/updateAccessToken','OauthController@updateAccessToken');
Route::post('/oauth/changePwd','OauthController@changePwd');


