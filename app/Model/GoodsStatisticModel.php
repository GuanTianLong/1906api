<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsStatisticModel extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'p_goods_statistic';

    /**
     * 表的主键id
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
