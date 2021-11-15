<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_sys_sms_log extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'sys_sms_log';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //添加时间字段
    const CREATED_AT = 'send_time';
    //修改时间字段
    const UPDATED_AT = '';
    //false=禁用自动填充时间戳
    public $timestamps = false;
    //时间使用时间戳
    public $dateFormat = 'U';
    //每页展示几笔
    public static $page_size = 10;
    //狀態
    public static $status_map = [
    ];

    protected function list_data(array $conds)
    {
        $page_size  = !empty($conds['page_size']) ? $conds['page_size']:self::$page_size;
        $page       = $conds['page'] ?? null;
        $order_by   = $conds['order_by'] ?? null;
        $count      = $conds['count'] ?? null;
        $limit      = $conds['limit'] ?? null;
        $index      = $conds['index'] ?? null;
        $group_by   = $conds['group_by'] ?? null;
        $field      = $conds['field'] ?? null;
        $next_page  = $conds['next_page'] ?? null;
        //手机号
        $phone       = !empty($conds['phone']) ? $conds['phone']:'';
        $date1 = empty($conds['date1']) ? '' :mod_common::date_convert_timestamp("{$conds['date1']} 00:00:00", mod_common::get_admin_timezone());
        $date2   = empty($conds['date2']) ? '' :mod_common::date_convert_timestamp("{$conds['date2']} 23:59:59", mod_common::get_admin_timezone());

        $where = [];
        $date1 and $where[] = ['send_time', '>=', (int)$date1]; //开始时间
        $date2 and $where[] = ['send_time', '<=', (int)$date2]; //结束时间
        $phone and $where[] = ['phone', 'like', "%{$phone}%"];

        $order_by = !empty($order_by) ? $order_by : ['send_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'uid', 'phone', 'content', 'send_time',
                'send_uid', 'status'
            ],
            'where'     => $where,
            'page'      => $page,
            'page_size' => $page_size,
            'order_by'  => $order_by,
            'group_by'  => $group_by,
            'count'     => $count,
            'limit'     => $limit,
            'index'     => $index,
            'field'     => $field,
            'next_page' => $next_page, //对于app,不需要计算总条数，只需返回是否需要下一页
        ]);
        //格式化数据
        if($count) {
            $rows['data'] = self::format_data($rows['data']);
        }
        else {
            $rows = self::format_data($rows);
        }

        return $rows;
    }

    protected function detail(array $conds)
    {
        $data = self::get_one(['where' => $conds]);

        if(!empty($data))
        {
            $data = self::format_data($data);
        }

        return $data;
    }

    //格式化数据
    private function format_data($data)
    {
        if(empty($data)) return $data;

        $list = is_array(reset($data)) ? $data : [$data];

        //获取会员
        $members = mod_user::list_data([
            'status'=>  mod_user::ENABLE,
            'index' => 'id',
        ]);

        foreach ($list as $k => $v)
        {
            $row_plus = [
                //用户名
                'username'  => array_key_exists($v['uid'], $members) ? $members[$v['uid']]['username'] : '',
                //状态
                'status_dis'       => array_key_exists($v['status'], self::$status_map) ? self::$status_map[$v['status']]:'',
                //发送日期
                'send_time_dis'  => mod_display::datetime($v['send_time']),
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    //保存
    protected function save_data(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'uid'       => '', //required
            'phone'     => '', //required
            'content'   => '', //required
            'send_uid'  => '', //发送人id,required
            'send_time' => '', //required
            'status'    => '', //required
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            self::insert_data($data_filter); //批量写入日志
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }

        if ($status > 0)
        {
            DB::commit();   //手動提交事务
        }
        else
        {
            DB::rollback(); //手動回滚事务
        }

        return $status;
    }

    //刪除
    protected function del_data(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $id = $data_filter['id'];
            unset($data_filter['id']);

            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            self::del(['id'=>$id]);
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }

        if ($status > 0)
        {
            DB::commit();   //手動提交事务
        }
        else
        {
            DB::rollback(); //手動回滚事务
        }

        return $status;
    }

    //所属会员, 使用方式 mod_sys_sms_log::find(1)->member_maps
    public function member_maps()
    {
        return $this->belongsTo('App\models\mod_user', 'uid', 'id');
    }
}
