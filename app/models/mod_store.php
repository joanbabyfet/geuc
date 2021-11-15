<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_store extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = false;
    //表名称
    public $table = 'store';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //狀態
    const REVIEW = 0;
    const ENABLE = 1;
    const DISABLE = -1;
    const FREEZE = -2;
    const REJECT = -3;
    public static $status_map = [
        self::REVIEW   => '待審核',
        self::ENABLE   => '啟用',
        self::DISABLE  => '禁用',
        self::FREEZE  => '凍結',
        self::REJECT  => '駁回',
    ];
    //图片压缩宽度
    public static $img_thumb_with = 750;

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
        $status     = $conds['status'] ?? null;

        $where = [];
        $where[] = ['delete_time', '=', 0];
        $name and $where[] = ['name', 'like', "%{$name}%"];
        is_numeric($status) and $where[] = ['status', '=', $status];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'shop_id', 'name', 'address', 'contact', 'sex', 'phone_code', 'phone',
                'status', 'create_user', 'create_time'
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

        //获取商家列表
        $shops = mod_shop::list_data([
            'index'    => 'id',
            'order_by' => ['create_time', 'asc']
        ]);

        foreach ($list as $k => $v)
        {
            //經營證件照
            $pictures = empty($v['pictures']) ? []:explode(',', $v['pictures']);
            $pictures_dis = [];
            $pictures_url_dis = [];
            foreach ($pictures as $picture)
            {
                $pictures_dis[] = $picture;
                $pictures_url_dis[] = mod_display::img($picture);
            }

            if(isset($v['content']))
            {
                $v['content'] = htmlspecialchars_decode($v['content']);
            }

            $row_plus = [
                //商家名称
                'shop_name_dis'   => array_key_exists($v['shop_id'], $shops) ? $shops[$v['shop_id']]['name'] : '',
                //店舖照片
                'pictures_dis'  => $pictures_dis,
                'pictures_url_dis'  => $pictures_url_dis,
                //聯絡電話
                'phone_dis'        => $v['phone_code'].$v['phone'],
                //状态
                'status_dis'       => array_key_exists($v['status'], self::$status_map) ? self::$status_map[$v['status']]:'',
                //添加日期
                'create_time_dis'  => mod_display::datetime($v['create_time']),
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    //保存
    protected function save_data(array $data)
    {
        $do             = isset($data['do']) ? $data['do'] : '';
        //参数过滤
        $data_filter = mod_common::data_filter([
            'do'            => 'required',
            'id'            => $do == 'edit' ? 'required' : '',
            'shop_id'       => 'required',
            'name'          => 'required',
            'country_id'    => 'required',
            'province_id'   => 'required',
            'city_id'       => 'required',
            'area_id'       => '',
            'address'       => 'required',
            'content'       => '',
            'pictures'      => '',
            'contact'       => 'required',
            'sex'           => 'required',
            'phone_code'    => 'required',
            'phone'         => 'required',
            'status'        => 'required',
            'create_user'   => '',
            'update_user'   => '',
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

            $do     = $data_filter['do'];
            $id    = $data_filter['id'];
            $create_user  = $data_filter['create_user'];
            $update_user  = $data_filter['update_user'];
            $data_filter['content']  = mod_common::htmlentities($data_filter['content']);
            $data_filter['shop_id'] = empty($data_filter['shop_id']) ? 0 : $data_filter['shop_id'];
            //店舖照片
            $pictures = empty($data_filter['pictures']) ? []: array_filter($data_filter['pictures']); //干掉空值
            unset($data_filter['do'], $data_filter['id'], $data_filter['create_user'], $data_filter['update_user']);

            if($do == 'add')
            {
                $data_filter['id'] = mod_common::random('web');
                $data_filter['pictures'] = implode(',', $pictures);
                $data_filter['create_time'] = time();
                $data_filter['create_user'] = $create_user;
                self::insert_data($data_filter);
            }
            elseif($do == 'edit')
            {
                $data_filter['pictures'] = implode(',', $pictures);
                $data_filter['update_time'] = time();
                $data_filter['update_user'] = $update_user;
                self::update_data($data_filter, ['id'=>$id]);
            }
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
            'delete_user'       => '',
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

            $data_filter['delete_time'] = time();
            self::update_data($data_filter, ['id'=>$id]);
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

    //啟用或禁用
    protected function change_status(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
            'update_user'       => '',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $id     = $data_filter['id'];
            unset($data_filter['id']);

            if(!is_array($data_filter) || !is_numeric($data_filter['status']))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            $data_filter['update_time'] = time();
            self::update_data($data_filter, ['id'=>$id]);
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

    //所属商家, 使用方式 mod_store::find('xxx')->shop_maps
    public function shop_maps()
    {
        return $this->belongsTo('App\models\mod_shop', 'shop_id', 'id');
    }
}
