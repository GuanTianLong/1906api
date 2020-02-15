<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class TestController extends Controller
{

    /**测试Redis*/
    public function testRedis(){
        $key = '1906';
        $val = time();
        //set 一个键 并赋值
        Redis::set($key,$val);
        //获取key的值
        $value = Redis::get($key);
        echo 'value:'.$value;
    }

    public function test001(){
        echo "hello word";
    }

    /**
        * 获取微信Access_token
        * 使用file_get_contents  发起GET请求
     */
    public function getAccessToken(){
        $app_id = "wx13d874725b17381e";

        $appsecret = '8372505d057f5dfd87ce69a0201bf3d5';

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$appsecret;
        echo $url;echo "<hr>";
        $response = file_get_contents($url);
        var_dump($response);
        //转化数据类型
        $arr = json_decode($response,true);
    }

    /**
        *使用curl发起GET请求
     */
    public function curlGet(){
        $app_id = "wx13d874725b17381e";

        $appsecret = '8372505d057f5dfd87ce69a0201bf3d5';

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$appsecret;
        echo $url;echo "<hr>";

        //初始化
        $ch = curl_init($url);

        //设置参数选项(0 启用浏览器输出   1 关闭浏览器输出   可用变量接收响应)
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        //执行会话
        $response = curl_exec($ch);

        //数据处理
        var_dump($response);

        //捕获错误
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        //有问题
        if($error>0){
            echo "错误码：".$errno;echo "<br>";
            echo "错误信息：".$error;die;
        }

        //关闭会话
        curl_close($ch);

    }

    /**
        *使用curl发起POST请求
     */
    public function curlPost(){
        $access_token = '30_9hzS1peruhIK36DkJnhTtk24QY4bwSMfyQcWGZ8JWTPCamoVIlR-cA6n3cSz4Rzo2xYskT9DjIFRMxOlHd58ro1_c6c6h07KNUumKnmIv5xvW8jizd233PeKrdrXvwtb43p6FEQd6rZ2PYKlEFXeAJAOPH';
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;

        $menu = [
            "button" => [
                [
                    "type" => "click",
                    "name" => "curlPost",
                    "key" => "curlPost001"
                ]
            ]
        ];

        //初始化
        $ch = curl_init($url);

        //设置参数选项
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        //POST请求
        curl_setopt($ch,CURLOPT_POST,true);

        //发送json数据  form-data形式
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json']);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($menu));

        //执行curl会话
        $response = curl_exec($ch);

        //数据处理
        var_dump($response);

        //捕获错误
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        //有问题
        if($error>0){
            echo "错误码：".$errno;echo "<br>";
            echo "错误信息：".$error;die;
        }

        //关闭会话
        curl_close($ch);
    }

    /**
        *使用guzzle发起GET请求
     */
    public function guzzleGet(){
        $app_id = "wx13d874725b17381e";

        $appsecret = '8372505d057f5dfd87ce69a0201bf3d5';

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$appsecret;
        echo $url;echo "<hr>";

        $client = new Client();
        $response = $client->request('GET',$url);
        //获取服务端响应的数据
        $data = $response->getBody();
        echo $data;
    }

    /**
        *处理GET请求的接口
     */
    public function get1(){
        echo "<pre>";print_r($_GET);echo "</pre>";
    }

    /**
        *处理curl---POST请求的接口(form-data传参)
     */
    public function post1(){
        echo "<hr>";
        echo "我是API的开始";
        echo "<pre>";print_r($_POST);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

    /**
        *处理curl---POST请求的接口(x-www-form-urlencoded传参)
     */
    public function post2(){
        echo "<hr>";
        echo "我是API的开始";
        echo "<pre>";print_r($_POST);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

    /**
        *处理curl---POST请求的接口(raw传参--发送json字符串，发送XML字符串)
     */
    public function post3(){
        echo "<hr>";
        echo "我是API的开始";
        //接收json或XML字符串
        $json = file_get_contents("php://input");
        //将接收的json数据转化为数组
        $arr = json_decode($json,true);
        echo "<pre>";print_r($arr);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

    /**
        *处理curl---POST请求的接口(上传文件)
     */
    public function testUpload(){
        echo "<hr>";
        echo "用户信息是:";
        echo "<pre>";print_r($_POST);echo "</pre>";
        echo "<hr>";
        echo "上传的文件是：";echo "<br>";
        echo "<pre>";print_r($_FILES);echo "</pre>";
        echo "<hr>";
    }

    /**
        *处理Guzzle请求的接口传过来的数据(GET方式)
     */
    public function guzzleGet1(){
        echo "<hr>";
        echo "我是API的开始";echo "<br>";
        echo "接收的数据：";echo "<br>";
        echo "<pre>";print_r($_GET);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

    /**
        *处理Guzzle请求的接口传过来的数据(POST方式)
     */
    public function guzzlePost1(){
        echo "<hr>";
        echo "我是API的开始";echo "<br>";
        echo "接收的数据：";echo "<br>";
        echo "<pre>";print_r($_POST);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

    /**
        *处理Guzzle请求的接口传过来的数据(POST方式--->文件上传)
     */
    public function guzzleUpload(){
        echo "<hr>";
        echo "我是API的开始";echo "<br>";
        echo "接收的数据是：";echo "<br>";
        echo "<pre>";print_r($_POST);echo "</pre>";
        echo "上传的文件是：";echo "<br>";
        echo "<pre>";print_r($_FILES);echo "</pre>";
        echo "我是API的结束";
        echo "<hr>";
    }

}
