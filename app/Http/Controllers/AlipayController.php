<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AlipayController extends Controller
{
    /**
        * 支付宝支付(沙箱环境)
     */
    public function payTest(){

        //沙箱支付接口
        $url = "https://openapi.alipaydev.com/gateway.do";

        //请求参数
        $common_param = [
            'out_trade_no'      => 'api1906_'.time().'_'.mt_rand(11111,99999),               //商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',                               //销售产品码，与支付宝签约的产品码名称。注：目前仅支持FAST_INSTANT_TRADE_PAY
            'total_amount'      => '100',                                                    //订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。
            'subject'            => '测试订单:'.mt_rand(11111,99999)                               //订单标题
        ];


        //公共请求参数
        $pub_param = [
            'app_id'        => env('ALIPAY_APPID'),               //支付宝分配给开发者的应用ID
            'method'        => 'alipay.trade.page.pay',               //接口名称
            'charset'       => 'utf-8',                               //请求使用的编码格式，如utf-8,gbk,gb2312等
            'sign_type'     => 'RSA2',                              //商户生成签名字符串所使用的签名算法类型，目前支持RSA2和RSA，推荐使用RSA2
            'timestamp'     => date('Y-m-d H:i:s'),        //发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
            'version'       => '1.0',                                 //调用的接口版本，固定为：1.0
            'biz_content'   => json_encode($common_param)                                 //请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递，具体参照各产品快速接入文档
        ];

        $params = array_merge($common_param,$pub_param);
        echo "排序前：<pre>";print_r($params);echo "</pre>";echo "<hr>";

        //筛选并排序
        ksort($params);
        echo "排序后：<pre>";print_r($params);echo "</pre>";

        //将筛选排序后的参数进行拼接
        $str = "";
        foreach($params as $key=>$val){
            $str .= $key.'='.$val.'&';
        }

        $str = rtrim($str,'&');
        echo "待签名的字符串：".$str;echo "<br>";

        //根据阿里云私钥生成$priv_key_id
        $priv_key_id = file_get_contents(storage_path('keys/priv_ali.key'));
        //echo "生成的key：".$priv_key_id;echo "<br>";

        //调用签名函数生成签名
        openssl_sign($str,$sign,$priv_key_id,OPENSSL_ALGO_SHA256);
        echo "生成的签名:".$sign;echo "<br>";

        //将生成的签名进行base64编码
        $base64_sign = base64_encode($sign);
        echo "base64编码后的签名：".$base64_sign;echo "<br>";

        //将base64编码后的签名加入url中参数中
        $request_url = $url.'?'.$str.'&sign='.urlencode($base64_sign);
        echo "请求的url：".$request_url;

        header("Location:".$request_url);

    }
}
