<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * 錢包轉帳日志
 * Class mod_transfer
 * @package App\models
 */
class mod_transfer extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'transfers';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //狀態
    const EXCHANGE = 'exchange';
    const TRANSFER = 'transfer';
    const PAID = 'paid';
    const REFUND = 'refund';
    const GIFT = 'gift';
    public static $status_map = [
        self::EXCHANGE   => '',
        self::TRANSFER  => '轉帳',
        self::PAID  => '',
        self::REFUND  => '',
        self::GIFT  => ''
    ];
}
