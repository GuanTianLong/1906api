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
    echo date("Y-m-d H:i:s");
    return view('welcome');
});

//phpinfo
Route::get('/phpinfo', function () {
    phpinfo();
});

/**测试路由分组*/
Route::prefix('/test')->group(function () {
    Route::get('/redis','TestController@testRedis');
    Route::get('/redis001','TestController@redis001');
    //使用file_get_contents  发起GET请求
    Route::get('/wx/token','TestController@getAccessToken');
    //使用curl发起GET请求
    Route::get('/curl/curlGet','TestController@curlGet');
    //使用curl发起POST请求
    Route::get('/curl/curlPost','TestController@curlPost');
    //使用guzzle发起GET请求
    Route::get('/guzzle/guzzleGet','TestController@guzzleGet');
    //处理GET请求的接口
    Route::get('/get1','TestController@get1');
    //处理POST请求的接口(form-data传参)
    Route::post('/post1','TestController@post1');
    //处理POST请求的接口(x-www-form-urlencoded传参)
    Route::post('/post2','TestController@post2');
    //处理POST请求的接口(raw传参--发送json字符串，发送XML字符串)
    Route::post('/post3','TestController@post3');
    //处理POST请求的接口(上传文件)
    Route::post('/testUpload','TestController@testUpload');
    //$_SERVER
    Route::get('/getUrl','TestController@getUrl');

});

/**Guzzle路由分组*/
Route::prefix('/guzzle')->group(function () {
    //接收guzzle发起GET请求
    Route::get('/guzzleGet1','TestController@guzzleGet1');
    //接收guzzle发起POST请求
    Route::post('/guzzlePost1','TestController@guzzlePost1');
    //接收guzzle发起POST请求(文件上传)
    Route::post('/guzzleUpload','TestController@guzzleUpload');

});

/**API路由分组*/
Route::prefix('/api')->group(function(){
    //获取用户信息
    Route::get('/user/info','Api\UserController@userInfo');
    //用户注册
    Route::post('/user/register','Api\UserController@register');
});

/**商品路由分组*/
Route::prefix('/goods')->group(function(){
    //商品详情页
    Route::get('/particular','Goods\GoodsController@particular');
    //商品缓存
    Route::get('/detial','Goods\GoodsController@detial');
    //商品缓存
    Route::get('/goods','Goods\GoodsController@goods');
});
