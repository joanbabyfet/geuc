<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * 錢包流水日志
 * Class mod_transaction
 * @package App\models
 */
class mod_transaction extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'transactions';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //類型
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    public static $type_map = [
        self::DEPOSIT   => '充值',
        self::WITHDRAW  => '提現'
    ];
}
