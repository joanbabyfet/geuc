<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use App\models\mod_common;
use App\models\mod_currency_type;
use App\models\mod_goods;
use App\models\mod_goods_cat;
use App\models\mod_goods_color;
use App\models\mod_store;
use Illuminate\Http\Request;

class ctl_goods extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size = (mod_common::get_action() == 'export_list') ?
            100 : $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $name    = $request->input('name') ?? '';

        //獲取數據
        $rows = mod_goods::list_data([
            'name'      =>  $name,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'name'                  =>'商品名稱',
                'store_name_dis'        =>'所屬店舖',
                'goods_cat_name_dis'    =>'商品分類',
                'status_dis'            =>'狀態',
                'create_time_dis'       =>'添加時間',
            ];

            return mod_common::export_data([
                'page_no'   => $page_no,
                'rows'      => $rows['data'],
                'file'      => $request->input('file', ''),
                'fields'    => $request->input('fields', []), //列表所有字段
                'titles'    => $titles, //輸出字段
                'total_page' => $pages->lastPage(),
            ]);
        }

        return view('admin.goods_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->index($request);
    }

    //添加
    public function add(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_goods::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //獲取店舖列表
            $stores = mod_store::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $store_options = mod_array::one_array($stores, ['id','name']);
            //获取币种
            $currency_options = mod_currency_type::get_currencys();
            //今天日期
            $timezone = mod_common::get_admin_timezone(); //例：ETC/GMT-7
            $today    = mod_common::format_date(time(), $timezone, 'Y/m/d');
            //獲取商品分類樹
//            $goods_cat_options = mod_goods_cat::get_tree([
//                'store_id'  => '50f9d2689aaa93222dbfd36a108d1cd9',
//                'status'    => mod_goods_cat::ENABLE,
//                'order_by'  => ['create_time', 'asc']
//            ]);
//            $goods_cat_options = empty($goods_cat_options) ?
//                '[]' : mod_common::array_to_str($goods_cat_options);
            //颜色
            $colors = mod_goods_color::list_data(['order_by'  => ['create_time', 'asc']]);

            return view('admin.goods_add', [
                'store_options' => $store_options,
                //'goods_cat_options' =>  $goods_cat_options,
                'currency_options' => $currency_options,
                'today'                 => $today,
                'colors'                => $colors,
            ]);
        }
    }

    //修改
    public function edit(Request $request)
    {
        $id = $request->input('id');
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_goods::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_goods::detail(['id' => $id]);
            //獲取店舖列表
            $stores = mod_store::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $store_options = mod_array::one_array($stores, ['id','name']);
            //獲取商品分類列表
//            $goods_cats = mod_goods_cat::list_data([
//                'order_by' => ['create_time', 'asc']
//            ]);
//            $goods_cat_options = mod_array::one_array($goods_cats, ['id','name']);
            //获取币种
            $currency_options = mod_currency_type::get_currencys();
            //今天日期
            $timezone = mod_common::get_admin_timezone(); //例：ETC/GMT-7
            $today    = mod_common::format_date(time(), $timezone, 'Y/m/d');
            //颜色
            $colors = mod_goods_color::list_data(['order_by'  => ['create_time', 'asc']]);

            return view('admin.goods_edit', [
                'row'   =>  $row,
                'store_options' => $store_options,
                //'goods_cat_options' => $goods_cat_options,
                'currency_options' => $currency_options,
                'today'                 => $today,
                'colors'                => $colors,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_goods::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'type'          => $request->input('type'),
            'cat_id'        => $request->input('cat_id'),
            'store_id'      => $request->input('store_id'),
            'sn'            => $request->input('sn'),
            'name'          => $request->input('name'),
            'name_en'       => $request->input('name_en'),
            'desc'          => $request->input('desc'),
            'desc_en'       => $request->input('desc_en'),
            'img'           => $request->input('img', []),
            'img_en'        => $request->input('img_en', []),
            'thumb'         => $request->input('thumb', []),
            'thumb_en'      => $request->input('thumb_en', []),
            'spec'          => $request->input('spec'),
            'spec_en'       => $request->input('spec_en'),
            'price'         => $request->input('price'),
            'origin_price'  => $request->input('origin_price'),
            'currency_code' => $request->input('currency_code'),
            'unit'          => $request->input('unit'),
            'stock'         => $request->input('stock'),
            'sold_num'      => $request->input('sold_num'),
            'limit_buy'     => $request->input('limit_buy'),
            'promotion_id'  => $request->input('promotion_id'),
            'color'         => $request->input('color', []),
            'is_hot'        => $request->input('is_hot', 0),
            'is_rec'        => $request->input('is_rec', 0),
            'is_new'        => $request->input('is_new', 0),
            'sort'          => $request->input('sort', 0),
            'status'        => $request->input('status', 0),
            'start_time'    => $request->input('start_time'),
            'end_time'      => $request->input('end_time'),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_goods::detail(['id' => $id]);

        return view('admin.goods_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_goods::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
