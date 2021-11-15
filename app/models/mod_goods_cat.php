<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_goods_cat extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'goods_cat';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //狀態
    const DISABLE = 0;
    const ENABLE = 1;
    public static $status_map = [
        self::DISABLE   => '禁用',
        self::ENABLE    => '啟用'
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
        $status     = $conds['status'] ?? null;
        $store_id   = !empty($conds['store_id']) ? $conds['store_id']:'';

        $where = [];
        $where[] = ['delete_time', '=', 0];
        $name and $where[] = ['name', 'like', "%{$name}%"];
        is_numeric($status) and $where[] = ['status', '=', $status];
        $store_id and $where[] = ['store_id', '=', $store_id];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'pid', 'level', 'store_id', 'name', 'status', 'create_time'
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

        //获取店铺列表
        $stores = mod_store::list_data([
            'index'    => 'id',
            'order_by' => ['create_time', 'asc']
        ]);

        foreach ($list as $k => $v)
        {
            $row_plus = [
                //店舖名称
                'store_name_dis'   => array_key_exists($v['store_id'], $stores) ? $stores[$v['store_id']]['name'] : '',
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
            'pid'           => '',
            'level'         => '',
            'childs'        => '',
            'store_id'      => 'required',
            'name'          => 'required',
            'name_en'       => '',
            'desc'          => '',
            'desc_en'       => '',
            'sort'          => '',
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
            $data_filter['pid'] = empty($data_filter['pid']) ? 0 : $data_filter['pid'];
            $create_user  = $data_filter['create_user'];
            $update_user  = $data_filter['update_user'];
            $data_filter['store_id'] = empty($data_filter['store_id']) ? 0 : $data_filter['store_id'];
            //狀態
            $data_filter['status'] = ($data_filter['status'] === 'on') ? 1:0;
            unset($data_filter['do'], $data_filter['id'], $data_filter['create_user'], $data_filter['update_user']);

            //获取当前节点层级
            $level = self::get_level($data_filter['pid']);

            if($do == 'add')
            {
                $data_filter['level'] = $level;
                $data_filter['create_time'] = time();
                $data_filter['create_user'] = $create_user;
                self::insert_data($data_filter);
            }
            elseif($do == 'edit')
            {
                $data_filter['level'] = $level;
                $data_filter['update_time'] = time();
                $data_filter['update_user'] = $update_user;
                self::update_data($data_filter, ['id'=>$id]);
            }

            //遍历更新所有节点层级与子节点2字段,通过id建立所有子分类id
            $cats = self::list_data([
                'index'    => 'id',
                'order_by' => ['create_time', 'asc']
            ]);
            $data_item = [];
            foreach($cats as $v)
            {
                $ids = mod_common::get_all_child_ids($cats, $v['id']);
                $data_item[] = [
                    'level'     => ($v['pid'] == 0) ? 0 : $cats[$v['pid']]['level'] + 1,
                    'childs'    => implode(',', $ids),
                    'id'        => $v['id'],
                ];
            }
            self::insertOrUpdate($data_item);
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

    //获取等级
    protected function get_level($pid = 0)
    {
        $level = 0;
        if(!empty($pid))
        {
            $level = self::get_field_value([
                'fields' => ['level'],
                'where' => [
                    ['id', '=', $pid]
                ]
            ]);
            $level += 1;
        }
        return $level;
    }

    //获取树形
    protected function get_tree(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'status'            => 'required',
            'store_id'          => '',
            'id'                => '', //有送分类id,则干掉自己及所有下级id
            'order_by'          => '',
        ], $data);

        $status = empty($data_filter['status']) ? mod_goods_cat::DISABLE : $data_filter['status'];
        $store_id = empty($data_filter['store_id']) ? 0 : $data_filter['store_id'];
        $id = empty($data_filter['id']) ? 0 : $data_filter['id'];
        $order_by = empty($data_filter['order_by']) ? ['create_time', 'asc'] : $data_filter['order_by'];

        //获取商品分類列表
        $cats = self::list_data([
            'index'         => 'id',
            'status'        =>  $status,
            'store_id'      =>  $store_id,
            'order_by'      =>  $order_by,
        ]);
        $ids = empty($id) ? [] : array_merge(mod_common::get_all_child_ids($cats, $id), [$id]);
        $pids = empty($id) ? [] : mod_common::get_all_parent_ids($cats, $id);

        $fields = ['id', 'pid', 'name', 'open']; //篩選需要的字段
        $rows = [];
        foreach ($cats as $k => $row)
        {
            //有送分类id,则干掉自己及所有下级id
            if(in_array($k, $ids)) continue;
            //判断是否自动展开节点
            $cats[$k]['open'] = in_array($k, $pids) ? 1 : 0;

            $data_item = [];
            foreach ($fields as $field) //匹配字段
            {
                isset($cats[$k][$field]) and $data_item[$field] = $cats[$k][$field];
            }
            $rows[$k] = $data_item;
        }
        return make_tree($rows, 'id', 'pid');
    }

    //生成html选单
    protected function make_menu(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'store_id'          => '',
        ], $data);

        //獲取商品分類樹
        $goods_cat_tree = self::get_tree([
            'store_id'  => $data_filter['store_id'],
            'status'    => mod_goods_cat::ENABLE,
            'order_by'  => ['create_time', 'asc']
        ]);
        return outToHtml($goods_cat_tree, route('web.products.index')."?cat_id=");
    }
}
