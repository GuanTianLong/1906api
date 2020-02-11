<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
}
