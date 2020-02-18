<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\GoodsStatisticModel;
use App\Model\GoodsModel;

class GoodsController extends Controller
{
    /**
        *商品详情页
     */
    public function particular(Request $request){
        //商品ID
        $goods_id = $request->get('goods_id');
        echo "商品ID：".$goods_id;echo "<br>";
        //获取客户端UA---用于标识UV
        $ua = $_SERVER['HTTP_USER_AGENT'];
        //获取客户端IP
        $ip = $_SERVER['REMOTE_ADDR'];

        $data = [
            'goods_id'     => $goods_id,
            'ua'            => $ua,
            'ip'            => $ip,
        ];

        //入库
        $id = GoodsStatisticModel::insertGetId($data);

        var_dump($id);echo "<hr>";

        //计算统计信息(页面浏览量,访客量)
        $pv = GoodsStatisticModel::where(['goods_id'=>$goods_id])->count();
        echo "当前(页面浏览量)PV：".$pv;echo "<br>";
        //访客量(UV)去重
        $uv = GoodsStatisticModel::where(['goods_id'=>$goods_id])->distinct('ua')->count('ua');
        echo "当前(访客量)UV：".$uv;echo "<br>";


    }

    /**
        *商品缓存
     */
    public function detial(Request $request){
        //商品ID
        $goods_id = $request->get('goods_id');
        $goods_key = 'str:goodsInfo'.$goods_id;
        echo "goods_key:".$goods_key;echo "<br>";

        //判断是否有缓存信息
        $cache = Redis::get($goods_key);
        //var_dump($cache);
        if($cache){
            echo "有缓存";echo "<br>";
            var_dump($cache);
        }else{
            echo "无缓存";echo "<br>";
            //去数据库中取数据，并保存到缓存中
            $goodsInfo = GoodsModel::where(['id'=>$goods_id])->first();
            //将对象转化为数组
            $arr = $goodsInfo->toArray();
            //将数组转化为json字符串
            $json_goodsInfo = json_encode($arr);
            //将查出来的数据保存至缓存中
            Redis::set($goods_key,$json_goodsInfo);

            echo "<pre>";print_r($json_goodsInfo);echo "</pre>";
        }
    }

    /**
        *商品页的访问量
     */
    public function goods(){
        $goodsInfo=GoodsStatisticModel::orderBy('id', 'desc')->limit(10)->get()->toArray();
        //dd($goodsInfo);
        $datanumber=count($goodsInfo);
        //dd($datanumber);
        if($datanumber>=10){
            //dd($goodsInfo);
            $datafirst=$goodsInfo[count($goodsInfo)-1];
            //dd($datafirst);
            $time=time()-strtotime($datafirst['created_at']);
            //dump($time);
            if($time<28860){
                //dd($time);
                echo "1分钟内访问不能大于10次";die;
            }

        }

    }

}
