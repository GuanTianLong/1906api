<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
        *接口防刷
     */
    public function api001()
    {

    }

    /**
        *获取天气
     */
    public function weather(){
        //判断
        if(empty($_GET['location'])){
            echo "请输入你所在的城市";
            die;
        }

        //城市名称
        $location = $_GET['location'];

        $url = 'https://free-api.heweather.net/s6/weather?location='.$location.'&keys=968ae0c640ea4a24a0a1334962b3e67b';
        $data = file_get_contents($url);
        //转化数据类型(将json数据类型转化为数组)
        $arr = json_decode($data,true);

        echo "获取到的天气信息：";
        echo "<pre>";print_r($arr);echo "</pre>";

        return $arr;
    }
}
