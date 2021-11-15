<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use App\models\mod_common;
use App\models\mod_role;
use App\models\mod_sys_sms;
use App\models\mod_user;
use Illuminate\Http\Request;

class ctl_sys_sms extends Controller
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
        $rows = mod_sys_sms::list_data([
            'name'      =>  $name,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['send_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'object_type_dis'   =>'發送對象',
                'name'          =>'短信名稱',
                'content'       =>'短信內容',
                'send_time_dis' =>'發送時間',
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

        return view('admin.sys_sms_index', [
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
            $status = mod_sys_sms::send([
                'object_type'   => $request->input('object_type'),
                'object_ids'    => $request->input('object_ids'),
                'name'          => $request->input('name'),
                'content'       => $request->input('content'),
                'content_en'    => $request->input('content_en'),
                'send_uid'      => $this->uid,
            ]);
            if($status < 0)
            {
                return mod_common::error(mod_sys_sms::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("系统短信添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //获取會員等級基础数据
            $member_levels = mod_role::list_data([
                'guard_name' => config('global.web.guard'),
                'order_by' => ['created_at', 'asc'],
            ]);
            $member_levels = mod_array::one_array($member_levels, ['id', 'name']);
            //获取會員
            $members = mod_user::list_data([
                'status'    => mod_user::ENABLE,
                'order_by'  => ['create_time', 'asc'],
            ]);
            $members = mod_array::one_array($members, ['id', 'phone']);

            return view('admin.sys_sms_add', [
                'member_levels' =>  $member_levels,
                'members' =>  $members,
            ]);
        }
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_sys_sms::del_data([
            'id'           => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_sys_sms::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("短信營銷刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
