<?php

namespace App\models;

use App\Jobs\job_send_sms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 系统短信
 * Class mod_sys_sms
 * @package App\models
 */
class mod_sys_sms extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'sys_sms';
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
    //短信发送对象
    const OBJECT_TYPE_ALL      = 1;
    const OBJECT_TYPE_PERSONAL = 2;
    const OBJECT_TYPE_LEVEL    = 3;
    const OBJECT_TYPE_REG_TIME = 4;
    public static $object_type = [
        1=>'所有用户',
        2=>'个人',
        3=>'会员等级',
        4=>'注册时间'
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
        //名稱
        $name       = !empty($conds['name']) ? $conds['name']:'';

        $where = [];
        $name and $where[] = ['name', 'like', "%{$name}%"];

        $order_by = !empty($order_by) ? $order_by : ['send_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'object_type', 'object_ids', 'name', 'content',
                'send_time', 'send_uid'
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

        foreach ($list as $k => $v)
        {
            $row_plus = [
                //發送對象類型
                'object_type_dis'   => array_key_exists($v['object_type'], self::$object_type) ? self::$object_type[$v['object_type']]:'',
                //发送日期
                'send_time_dis'     => mod_display::datetime($v['send_time']),
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    /**
     * 發送短信 (外部调用这里)
     * @param array $data
     * @return int|mixed
     */
    protected function send(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'object_type'   => 'required',
            'object_ids'    => '',
            'name'          => 'required', //短信名称
            'content'       => 'required',
            'content_en'    => 'required',
            'send_uid'      => 'required',
        ], $data);

        $status = 1;
        try
        {
            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            $data_filter['object_ids'] = empty($data_filter['object_ids']) ?
                '' : $data_filter['object_ids'];
            $content       = $data_filter['content'];
            $content_en    = $data_filter['content_en'];
            $send_uid    = $data_filter['send_uid'] ?? '';

            //$where = ['status' => 1]; //激活
            $uids = [];
            $start_time = '';
            $end_time = '';
            switch ($data_filter['object_type'])
            {
                case self::OBJECT_TYPE_ALL:
                    break;
                case self::OBJECT_TYPE_PERSONAL:
                    $uids = explode(',', $data_filter['object_ids']);
                    $uids = empty($uids) || empty($data_filter['object_ids']) ?
                        [-1] : $uids;
                    //用户id
                    //$where[] = ['id', 'in', $uids];
                    break;
                case self::OBJECT_TYPE_LEVEL:
                    $level_ids = explode(',', $data_filter['object_ids']);
                    $level_ids = empty($level_ids) || empty($data_filter['object_ids']) ?
                        [] : $data_filter['object_ids'];

                    //获取该用户组有哪些用户
                    $uids = [];
                    if(!empty($level_ids))
                    {
                        $users = mod_model_has_roles::list_data([
                            'role_id'       => $level_ids,
                            'model_type'    => get_class(new mod_user())
                        ]);
                        $uids = mod_array::sql_in($users, 'model_id');
                    }
                    $uids = empty($uids) ? [-1] : $uids;
                    //会员等级id
                    //$where[] = ['id', 'in', $uids];
                    break;
                case self::OBJECT_TYPE_REG_TIME:
                    if (!empty($data_filter['object_ids']) && strpos($data_filter['object_ids'], ',') !== false)
                    {
                        list($start_date, $end_date) = explode(',', $data_filter['object_ids']);
                        $start_time = empty($start_date) ? '' : $start_date;
                        $end_time   = empty($end_date) ? '' : $end_date;
                    }
//                    if (!empty($start_time) && !empty($end_time) && $start_time < $end_time)
//                    {
//                        $where[] = ['create_time', '>=', $start_time];
//                        $where[] = ['create_time', '<=', $end_time];
//                    }
                    break;
                default:
                    self::exception('发送对象類型错误', -2);
            }

            $add = array_merge($data_filter, ['send_time' => time()]);
            $send_id = self::insert_data($add);
            if (empty($send_id))
            {
                self::exception('插入发送记录失败', -3);
            }

            $page_no = 1;
            do
            {
                //获取会员id,手机号,设置语言
                $rows = mod_user::list_data([
                    'page'      =>  $page_no,
                    'page_size' =>  500,
                    'status'     =>  mod_user::ENABLE,
                    'date1'     =>  $start_time,
                    'date2'     =>  $end_time,
                    'id'        => $uids,
                    'order_by'  => ['create_time', 'asc'],
                ]);

                $fields = ['id', 'phone', 'language']; //篩選需要的字段
                $send_users = [];
                foreach ($rows as $k => $row)
                {
                    $data_item = [];
                    foreach ($fields as $field) //匹配字段
                    {
                        isset($rows[$k][$field]) and $data_item[$field] = $rows[$k][$field];
                    }
                    $send_users[] = $data_item;
                }

                if (empty($send_users))
                {
                    break;
                }

                //執行腳本,将任务放入异步队列中
                $params = [
                    'content'       => $content,
                    'content_en'    => $content_en,
                    'send_users'    => $send_users,
                    'send_uid'      => $send_uid,
                ];
                $job = new job_send_sms($params);
                dispatch($job);

                $page_no++;
            }
            while (!empty($rows));

            if (empty($rows) && $page_no === 1)
            {
                self::exception('发送对象不存在', -4);
            }
        }
        catch (\Exception $e)
        {
            $status = $e->getCode();
            //記錄日誌
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }

        return $status;
    }

    /**
     * 發送短信
     * @param array $data
     * @return bool
     */
    protected function _send_sms(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'content'       => 'required',
            'content_en'    => 'required',
            'send_users'    => 'required',
            'send_uid'      => '',
        ], $data);

        $status = 1;
        try
        {
            $send_users             = $data_filter['send_users'];
            $log_data = [];
            foreach($send_users as $send_user)
            {
                if (empty($send_user['phone'])) continue;
                $lang = in_array($send_user['language'], ['zh-tw', 'en']) ?
                    $send_user['language'] : 'zh-tw';
                $content_field = mod_common::get_lang_field('content', $lang);
                $app_name  = '禹臣實業';
                $msg = "【{$app_name}】{$data_filter[$content_field]}";
                //手机号
                $send_user['phone'] = str_replace(' ', '', $send_user['phone']);
                $send_user['phone'] = trim($send_user['phone']);

                //调试状态下，只记录不发送
                if (!config('app.debug'))
                {
                    //傳送簡訊
                    $sms = new mod_smshttp();
                    $userID     = config('global.every8d_sms.username');	//發送帳號
                    $password   = config('global.every8d_sms.password');	//發送密碼
                    $subject    = "测试";	//簡訊主旨
                    $content    = $msg;	//簡訊內容
                    $mobile     = $send_user['phone'];	//接收人之手機號碼
                    $sendTime   = ""; //簡訊預定發送時間
                    $status     = $sms->sendSMS($userID,$password,$subject,$content,$mobile,$sendTime);
                    if(!$status)
                    {
                        self::exception($sms->processMsg, -1);
                    }
                }

                $log_data[] = [
                    'uid'       => $send_user['id'], //接收人id
                    'phone'     => $send_user['phone'],//接收人手机号
                    'content'   => $msg,
                    'send_uid'  => $data_filter['send_uid'], //发送人id
                    'send_time' => time(), //发送时间
                    'status'    => (int)$status //发送状态
                ];
                //避免太過頻繁的查詢
                usleep(10000);  //让进程挂起一段时间,避免cpu跑100%(单位微秒 1秒=1000000)
            }

            if (!empty($log_data)) //批量写入短信日志
            {
                mod_sys_sms_log::save_data($log_data);
            }
        }
        catch (\Exception $e)
        {
            $status = $e->getCode();
            //記錄日誌
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'args'    => func_get_args()
            ]);
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
}
