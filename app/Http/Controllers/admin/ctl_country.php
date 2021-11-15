<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_country;
use App\models\mod_redis;

class ctl_country extends Controller
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
        $rows = mod_country::list_data([
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
                'name'             =>'國家名稱',
                'en_short_name'    =>'英文簡稱',
                'is_default_dis'   =>'默認展示',
                'is_fix_dis'       =>'固定頻道',
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

        return view('admin.country_index', [
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
                return mod_common::error(mod_country::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("國家添加 ");

            return mod_common::success([],trans('api.api_add_success'));
        }
        else
        {
            return view('admin.country_add', []);
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
                return mod_common::error(mod_country::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("國家修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_country::detail(['id' => $id]);

            return view('admin.country_edit', [
                'row'   =>  $row,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_country::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'en_short_name' => $request->input('en_short_name'),
            'en_name'       => $request->input('en_name'),
            'mobile_prefix' => $request->input('mobile_prefix'),
            'is_fix'        => $request->input('is_fix', 0),
            'is_default'    => $request->input('is_default', 0),
            'sort'          => $request->input('sort'),
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
        $row = mod_country::detail(['id' => $id]);

        return view('admin.country_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_country::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_country::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("國家刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_country::change_status([
            'id'        => $id + [-1],
            'status'    => mod_country::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_country::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("國家啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_country::change_status([
            'id'        => $id + [-1],
            'status'    => mod_country::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_country::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("國家禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
