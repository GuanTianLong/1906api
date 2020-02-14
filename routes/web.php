<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//phpinfo
Route::get('/phpinfo', function () {
    phpinfo();
});

/**测试路由分组*/
Route::prefix('/test')->group(function () {
    Route::get('/redis','TestController@testRedis');
    Route::get('/test001','TestController@test001');
    //使用file_get_contents  发起GET请求
    Route::get('/wx/token','TestController@getAccessToken');
    //使用curl发起GET请求
    Route::get('/curl/curlGet','TestController@curlGet');
    //使用curl发起POST请求
    Route::get('/curl/curlPost','TestController@curlPost');
    //使用guzzle发起GET请求
    Route::get('/guzzle/guzzleGet','TestController@guzzleGet');
});

/**API路由分组*/
Route::prefix('/api')->group(function(){
    //获取用户信息
    Route::get('/user/info','Api\UserController@userInfo');
    //用户注册
    Route::post('/user/register','Api\UserController@register');
});
