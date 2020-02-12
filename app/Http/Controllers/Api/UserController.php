<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;

class UserController extends Controller
{
    /**
        *获取用户信息
        * 2020/2/12 10:28
     */
    public function userInfo(){
        $info = [
            'name' => 'long',
            'email' => 'long@qq.com',
            'sex' => '男',
            'age' => '20',
            'time' => date('Y-m-d H:i:s')
        ];

        return $info;

    }

    /**
        *用户注册
        *2020/2/12 10:43
     */
    public function register(Request $request){
        //接收数据
        $data = $request->input();
        $user_name = $request->input('user_name');
        //echo 'user_name'.$user_name;

        $user_info = [
            'user_name' => $request->input('user_name'),
            'email'     => $request->input('email'),
            'pass'      => 'root'
        ];

        //入库
        $id = UserModel::insertGetId($user_info);

        echo "自增ID：".$id;
    }

}
