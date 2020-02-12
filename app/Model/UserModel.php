<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'p_users';

    /**
     * 表的主键id
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
