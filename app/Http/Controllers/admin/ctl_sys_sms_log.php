<?php

namespace App\Http\Controllers\admin;

use App\models\mod_sys_sms_log;
use App\models\mod_admin_user_oplog;
use App\models\mod_common;
use App\models\mod_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ctl_sys_sms_log extends Controller
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
        $phone    = $request->input('phone') ?? '';
        $date1      = $request->input('date1');
        $date2      = $request->input('date2');

        //獲取數據
        $rows = mod_sys_sms_log::list_data([
            'phone'     =>  $phone,
            'date1'     =>  $date1,
            'date2'     =>  $date2,
            'page'      =>  (int)$page_no, //mongo会比对類型
            'page_size' =>  (int)$page_size,
            'count'     => 1,
            'order_by'  => ['send_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'id'            =>'ID',
                'username'      =>'用户名',
                'phone'         =>'手机号',
                'content'       =>'短信内容',
                'send_time_dis' =>'发送时间',
                'status_dis'    =>'状态',
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

        return view('admin.sys_sms_log_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->index($request);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_sys_sms_log::del_data([
            'id'           => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_sys_sms_log::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("短信日志刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
