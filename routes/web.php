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
    //测试Redis
    Route::get('/redis','TestController@testRedis');
    //Redis计数防刷
    Route::get('/redis/redisCount','TestController@redisCount');
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
    //$_SERVER(获取当前完整的URL地址)
    Route::get('/getUrl','TestController@getUrl');
    //数据签名(发送端)
    Route::get('/send/signature','TestController@signature');
    //数据签名(接收端)
    Route::get('/receiver/signature1','TestController@signature1');
    //计算运气
    Route::get('/luck','TestController@luck');
    //字符串解密
    Route::get('/decrypt','TestController@decrypt');
    //数据对称加密的解密
    Route::get('/decrypt1','TestController@decrypt1');
    //数据对称加密的解密+数据签名
    Route::get('/decrypt2','TestController@decrypt2');
    //非对称加密,接收加密数据(使用私钥解密)
    Route::get('/rsa/decrypt','TestController@rsaDecrypt');
    //使用非对称加密验证签名
    Route::get('/rsa/verify','TestController@rsaVerify');

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

/**用户路由分组*/
Route::prefix('/user')->group(function(){
    //获取用户信息
    Route::get('/info','Api\UserController@userInfo');
    //用户注册
    Route::get('/register','Api\UserController@register');
});

/**API路由分组*/
Route::prefix('/api')->group(function(){
    //接口防刷
    Route::get('/api001','Api\ApiController@api001');
    //天气接口
    Route::get('/weather','Api\ApiController@weather');

});

/**商品路由分组*/
Route::prefix('/goods')->group(function(){
    //商品详情页
    Route::get('/particular','Goods\GoodsController@particular');
    //商品缓存
    Route::get('/detial','Goods\GoodsController@detial');
});
