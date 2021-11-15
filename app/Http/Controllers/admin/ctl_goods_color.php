<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_common;
use App\models\mod_goods_color;
use Illuminate\Http\Request;

class ctl_goods_color extends Controller
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
        $rows = mod_goods_color::list_data([
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
                'name'              =>'名稱',
                'status_dis'        =>'狀態',
                'create_time_dis'   =>'添加時間',
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

        return view('admin.goods_color_index', [
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
                return mod_common::error(mod_goods_color::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品顏色添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            return view('admin.goods_color_add', [
                'img_thumb_with'    =>  mod_goods_color::$img_thumb_with,
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
                return mod_common::error(mod_goods_color::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("商品顏色修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_goods_color::detail(['id' => $id]);

            return view('admin.goods_color_edit', [
                'row'   =>  $row,
                'img_thumb_with'    =>  mod_goods_color::$img_thumb_with,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_goods_color::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'img'           => $request->input('img', []),
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
        $row = mod_goods_color::detail(['id' => $id]);

        return view('admin.goods_color_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods_color::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_color::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品顏色刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_goods_color::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods_color::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_color::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品顏色啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_goods_color::change_status([
            'id'        => $id + [-1],
            'status'    => mod_goods_color::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_goods_color::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("商品顏色禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
