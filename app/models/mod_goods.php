<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_goods extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = false;
    //表名称
    public $table = 'goods';
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
    //商品类型
    public static $type_map = [
        1 => '實物商品', //(需要物流)
        2 => '虛擬商品', //(無需物流)
        3 => '電子卡密', //(無需物流)
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
        //分類id
        $cat_id       = !empty($conds['cat_id']) ? $conds['cat_id']:'';
        //名稱
        $name       = !empty($conds['name']) ? $conds['name']:'';
        //類型 new=新品 hot=熱門 rec=推薦
        $type       = !empty($conds['type']) ? $conds['type']:'';
        $status     = $conds['status'] ?? null;
        //id
        $id       = !empty($conds['id']) ? $conds['id']:'';
        //排除当前
        $exclude     = $conds['exclude'] ?? null;

        $where = [];
        $where[] = ['delete_time', '=', 0];
        $name and $where[] = ['name', 'like', "%{$name}%"];
        $cat_id and $where[] = ['cat_id', 'in', is_array($cat_id) ? $cat_id : explode(',', $cat_id)];
        is_numeric($status) and $where[] = ['status', '=', $status];
        in_array($type, ['new', 'hot', 'rec']) and $where[] = ["is_{$type}", '=', 1];
        $id and $where[] = ['id', 'in', is_array($id) ? $id : explode(',', $id)];
        $exclude and $where[] = ['id', 'not in', is_array($exclude) ? $exclude : explode(',', $exclude)];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'type', 'store_id', 'cat_id', 'sn', 'name',
                'img', 'img_en', 'thumb', 'thumb_en', 'spec', 'spec_en', 'price', 'origin_price',
                'currency_code', 'stock', 'status', 'start_time', 'end_time', 'create_time'
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

        //获取商品分类
        $goods_cats = mod_goods_cat::list_data([
            'index'    => 'id',
            'order_by' => ['create_time', 'asc']
        ]);
        //获取所属店铺
        $stores = mod_store::list_data([
            'index'    => 'id',
            'order_by' => ['create_time', 'asc']
        ]);

        foreach ($list as $k => $v)
        {
            //商品列表小圖
            $thumbs = empty($v['thumb']) ? []:explode(',', $v['thumb']);
            $thumb_dis = [];
            $thumb_url_dis = [];
            foreach ($thumbs as $thumb)
            {
                $thumb_dis[] = $thumb;
                $thumb_url_dis[] = mod_display::img($thumb);
            }
            $thumb_ens = empty($v['thumb_en']) ? []:explode(',', $v['thumb_en']);
            $thumb_en_dis = [];
            $thumb_en_url_dis = [];
            foreach ($thumb_ens as $thumb_en)
            {
                $thumb_en_dis[] = $thumb_en;
                $thumb_en_url_dis[] = mod_display::img($thumb_en);
            }
            //商品詳情大圖
            $imgs = empty($v['img']) ? []:explode(',', $v['img']);
            $img_dis = [];
            $img_url_dis = [];
            foreach ($imgs as $img)
            {
                $img_dis[] = $img;
                $img_url_dis[] = mod_display::img($img);
            }
            $img_ens = empty($v['img_en']) ? []:explode(',', $v['img_en']);
            $img_en_dis = [];
            $img_en_url_dis = [];
            foreach ($img_ens as $img_en)
            {
                $img_en_dis[] = $img_en;
                $img_en_url_dis[] = mod_display::img($img_en);
            }

            if(isset($v['desc']))
            {
                $v['desc'] = htmlspecialchars_decode($v['desc']);
            }
            if(isset($v['desc_en']))
            {
                $v['desc_en'] = htmlspecialchars_decode($v['desc_en']);
            }

            $row_plus = [
                //商品列表小圖
                'thumb_dis'      => $thumb_dis,
                'thumb_url_dis'  => $thumb_url_dis,
                'thumb_en_dis'      => $thumb_en_dis,
                'thumb_en_url_dis'  => $thumb_en_url_dis,
                //商品詳情大圖
                'img_dis'      => $img_dis,
                'img_url_dis'  => $img_url_dis,
                'img_en_dis'      => $img_en_dis,
                'img_en_url_dis'  => $img_en_url_dis,
                //商品類型
                'type_dis'       => array_key_exists($v['type'], self::$type_map) ? self::$type_map[$v['type']]:'',
                //商品分类
                'goods_cat_name_dis'   => array_key_exists($v['cat_id'], $goods_cats) ? $goods_cats[$v['cat_id']]['name'] : '',
                //店舖名称
                'store_name_dis'   => array_key_exists($v['store_id'], $stores) ? $stores[$v['store_id']]['name'] : '',
                //状态
                'status_dis'       => array_key_exists($v['status'], self::$status_map) ? self::$status_map[$v['status']]:'',
                //上架日期
                'start_time_dis'  => mod_display::date($v['start_time']),
                'end_time_dis'  => mod_display::date($v['end_time']),
                //添加日期
                'create_time_dis'  => mod_display::datetime($v['create_time']),
                //颜色
                'color_arr'  => empty($v['color']) ? []:explode(',', $v['color']),
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
            'type'          => 'required',
            'cat_id'        => 'required',
            'store_id'      => 'required',
            'sn'            => '',
            'name'          => 'required',
            'name_en'       => '',
            'desc'          => '',
            'desc_en'       => '',
            'img'           => '',
            'img_en'        => '',
            'thumb'         => '',
            'thumb_en'      => '',
            'spec'          => '',
            'spec_en'       => '',
            'price'         => 'required',
            'origin_price'  => '',
            'currency_code' => 'required',
            'unit'          => '',
            'stock'         => '',
            'sold_num'      => '',
            'limit_buy'     => '',
            'promotion_id'  => '',
            'color'         => '',
            'accessory'     => '',
            'is_hot'        => 'required',
            'is_rec'        => 'required',
            'is_new'        => 'required',
            'sort'          => '',
            'status'        => 'required',
            'start_time'    => '',
            'end_time'      => '',
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
            $data_filter['desc']  = mod_common::htmlentities($data_filter['desc']);
            $data_filter['desc_en']  = mod_common::htmlentities($data_filter['desc_en']);
            //狀態
            $data_filter['status'] = ($data_filter['status'] === 'on') ? 1:0;
            //热门
            $data_filter['is_hot'] = ($data_filter['is_hot'] === 'on') ? 1:0;
            //推荐
            $data_filter['is_rec'] = ($data_filter['is_rec'] === 'on') ? 1:0;
            //新品
            $data_filter['is_new'] = ($data_filter['is_new'] === 'on') ? 1:0;
            //上架时间
            $data_filter['start_time'] = empty($data_filter['start_time']) ? 0 :
                mod_common::date_convert_timestamp("{$data_filter['start_time']}", mod_common::get_admin_timezone());
            $data_filter['end_time']   = empty($data_filter['end_time']) ? 0 :
                mod_common::date_convert_timestamp("{$data_filter['end_time']}", mod_common::get_admin_timezone());
            //商品列表小圖
            $thumb = empty($data_filter['thumb']) ? []: array_filter($data_filter['thumb']); //干掉空值
            $thumb_en = empty($data_filter['thumb_en']) ? []: array_filter($data_filter['thumb_en']); //干掉空值
            //商品詳情大圖
            $img = empty($data_filter['img']) ? []: array_filter($data_filter['img']); //干掉空值
            $img_en = empty($data_filter['img_en']) ? []: array_filter($data_filter['img_en']); //干掉空值
            //颜色
            $data_filter['color'] = empty($data_filter['color']) ? '':implode(',', $data_filter['color']);
            //配件
            $data_filter['accessory'] = empty($data_filter['accessory']) ? '':implode(',', $data_filter['accessory']);

            unset($data_filter['do'], $data_filter['id'], $data_filter['create_user'], $data_filter['update_user']);

            if($do == 'add')
            {
                $data_filter['id'] = mod_common::random('web');
                $data_filter['thumb'] = implode(',', $thumb);
                $data_filter['thumb_en'] = implode(',', $thumb_en);
                $data_filter['img'] = implode(',', $img);
                $data_filter['img_en'] = implode(',', $img_en);
                $data_filter['create_time'] = time();
                $data_filter['create_user'] = $create_user;
                self::insert_data($data_filter);
            }
            elseif($do == 'edit')
            {
                $data_filter['thumb'] = implode(',', $thumb);
                $data_filter['thumb_en'] = implode(',', $thumb_en);
                $data_filter['img'] = implode(',', $img);
                $data_filter['img_en'] = implode(',', $img_en);
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

    //获取配件
    protected function get_accessories(array $conds)
    {
        return $this->list_data($conds);
    }
}
