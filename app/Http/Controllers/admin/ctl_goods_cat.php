<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use App\models\mod_common;
use App\models\mod_goods_cat;
use App\models\mod_store;
use Illuminate\Http\Request;

class ctl_goods_cat extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $name    = $request->input('name') ?? '';
        $store_id    = $request->input('store_id') ?? '';

        //獲取店舖列表
        $stores = mod_store::list_data([
            'order_by' => ['create_time', 'asc']
        ]);
        $store_options = mod_array::one_array($stores, ['id','name']);

        //獲取數據
        $rows = mod_goods_cat::list_data([
            'store_id'  =>  $store_id,
            'name'      =>  $name,
            'order_by'  => ['create_time', 'asc'],
        ]);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {

        }

        return view('admin.goods_cat_index', [
            'list'      =>  empty($rows) ? '[]' : mod_common::array_to_str($rows),
            'store_options' =>  $store_options,
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
                return mod_common::error(mod_goods_cat::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品分類添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //獲取店舖列表
            $stores = mod_store::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $store_options = mod_array::one_array($stores, ['id','name']);

            return view('admin.goods_cat_add', [
                'store_options' => $store_options,
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
                return mod_common::error(mod_goods_cat::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品分類修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            //獲取店舖列表
            $stores = mod_store::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $store_options = mod_array::one_array($stores, ['id','name']);

            $row = mod_goods_cat::detail(['id' => $id]);

            return view('admin.goods_cat_edit', [
                'row'   =>  $row,
                'store_options' => $store_options,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_goods_cat::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'pid'           => $request->input('pid', 0),
            'store_id'      => $request->input('store_id', 0),
            'name'          => $request->input('name'),
            'name_en'       => $request->input('name_en'),
            'desc'          => $request->input('desc'),
            'desc_en'       => $request->input('desc_en'),
            'sort'          => $request->input('sort', 0),
            'status'        => $request->input('status', 0),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_goods_cat::detail(['id' => $id]);

        return view('admin.goods_cat_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods_cat::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_cat::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品分類刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_goods_cat::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods_cat::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_cat::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品分類啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods_cat::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods_cat::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_cat::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品分類禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
