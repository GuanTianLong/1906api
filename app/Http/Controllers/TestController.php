<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class TestController extends Controller
{

    /**
        * 测试Redis
     */
    public function testRedis(){
        $key = '1906';
        $val = time();
        //set 一个键 并赋值
        Redis::set($key,$val);
        //获取key的值
        $value = Redis::get($key);
        echo 'value:'.$value;
    }

    /**
        *Redis计数防刷
     */
    public function redisCount(Request $request){
        //通过获取客户端UA来识别用户
        $ua = $_SERVER['HTTP_USER_AGENT'];
        echo "当前客户端UA：".$ua;echo "<br>";

        //将获取到的UA转化为md格式
        $md_ua = md5($ua);
        echo "转化后的UA(md5)：".$md_ua;echo "<br>";

        //截取UA(md5)中的5位(从第3位开始)
        $sub_ua = substr($md_ua,3,5);
        echo "截取后的UA：".$sub_ua;echo "<hr>";

        //接口访问限制次数
        $maxCount = env('API_ACCESS_COUNT');
        echo "限制访问次数：".$maxCount;echo "<br>";

        //判断访问次数是否已达上限(将截取后的UA拼上$key)
        $key = $sub_ua.'redisCount';
        echo "拼接后的key：".$key;echo "<br>";

        //取出现有访问次数
        $number = Redis::get($key);
        echo "现有访问次数：".$number;echo "<br>";

        //判断现有访问次数是否已经超过了限制次数
        if($number>$maxCount){
            //接口访问超时秒数
            $timeOut = env('API_TIMEOUT_SECOND');
            //设置当前key的过期时间(10秒内禁止访问)
            Redis::expire($key,$timeOut);

            echo "接口访问次数受限，超过了限制次数:".$maxCount;echo "<br>";
            echo "请您".$timeOut."秒后再来访问，谢谢！";echo "<br>";
            die;
        }

        //统计访问次数
        $redisCount = Redis::incr($key);
        echo "访问正常";

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

    /**
        *$_SERVER
        * 获取当前完整的URL地址
     */
    public function getUrl(){
        //协议(Http,Https)
        $cheme = $_SERVER['REQUEST_SCHEME'];

        //域名
        $host = $_SERVER['HTTP_HOST'];

        //请求URI
        $uri = $_SERVER['REQUEST_URI'];

        //拼完整的URL
        $url = $cheme .'://'.$host.$uri;

        echo "当前URL：".$url;echo "<hr>";

        echo "<pre>";print_r($_SERVER);echo "</pre>";
    }

    /**
        *数据签名(发送端)
     */
    public function signature(){
        //发送端和接收端必须使用同一个签名
        $key = '1906api';

        //签名数据
        $str = $_GET['str'];
        echo "签名前的数据：".$str;echo "<br>";

        //计算签名 md5  (原始数据+key)
        $result = md5($str.$key);
        echo "计算的签名：".$result;

        //发送数据(原始数据+计算的签名一起发送)

    }

    /**
        *数据签名(接收端)，接收数据，验证签名
     */
    public function signature1(){
        //发送端和接收端必须使用同一个签名
        $key = '1906api';

        //接收到的数据
        $data = $_GET['data'];

        //接收到的签名
        $sign = $_GET['sign'];

        //验签(需要与发送端使用相同的规则)
        $result1 = md5($data.$key);
        echo "接收端计算的签名：".$result1;echo "<br>";

        //与接收的签名对比(看是否一致)
        if($result1 == $sign){
            echo "验签通过，数据完整";
        }else{
            echo "验签失败，数据损坏";
        }

    }

}
