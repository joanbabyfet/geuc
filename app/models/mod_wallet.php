<?php

namespace App\models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 用戶錢包
 * Class mod_wallet
 * @package App\models
 */
class mod_wallet extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'wallets';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;

    //过过允许充值或提现
    protected function confirm(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
        ], $data);

        $status = 1;
        try
        {
            if(!is_array($data_filter))
            {
                mod_model::exception("{$data_filter}", -1);
            }
            $id         = $data_filter['id'];
            unset($data_filter['id']);

            $transaction = Transaction::find( $id );
            $transaction->wallet->confirm($transaction);
        }
        catch (\Exception $e)
        {
            $status = mod_model::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }
        return $status;
    }
}
