<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class ApiFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //获取当前请求的路径
        $uri = $_SERVER['REQUEST_URI'];
        //echo "当前请求的路径：".$uri;echo "<br>";

        //将获取到的URI转化为md格式
        $md_uri = md5($uri);
        //echo "转化后的URI(md5)：".$md_uri;echo "<br>";

        //截取URI(md5)中的5位(从第3位开始)
        $sub_uri = substr($md_uri,3,5);
        //echo "截取后的URI：".$sub_uri;echo "<hr>";

        //通过获取客户端UA来识别用户
        $ua = $_SERVER['HTTP_USER_AGENT'];
        //echo "当前客户端UA：".$ua;echo "<br>";

        //将获取到的UA转化为md格式
        $md_ua = md5($ua);
        //echo "转化后的UA(md5)：".$md_ua;echo "<br>";

        //截取UA(md5)中的5位(从第3位开始)
        $sub_ua = substr($md_ua,3,5);
        //echo "截取后的UA：".$sub_ua;echo "<br>";

        $key = 'count:uri:'.$sub_uri.':'.$sub_ua;
        echo "当前的key：".$key;echo "<br>";

        //接口访问限制次数
        $maxCount = env('API_ACCESS_COUNT');
        echo "限制访问次数：".$maxCount;echo "<br>";

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
        echo "访问正常";echo "<hr>";

        return $next($request);
    }
}
