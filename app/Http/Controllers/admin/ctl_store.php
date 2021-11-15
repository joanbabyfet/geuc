<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_area;
use App\models\mod_array;
use App\models\mod_common;
use App\models\mod_country;
use App\models\mod_shop;
use App\models\mod_store;
use Illuminate\Http\Request;

class ctl_store extends Controller
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
        $rows = mod_store::list_data([
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
                'name'              =>'店舖名稱',
                'shop_name_dis'     =>'所屬商家',
                'contact'           =>'聯絡人',
                'phone_dis'         =>'聯絡電話',
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

        return view('admin.store_index', [
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
                return mod_common::error(mod_store::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("店舖添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //获取手机国码
            $mobile_prefix_options = mod_country::get_mobile_prefix();
            //获取商家列表
            $shops = mod_shop::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $shop_options = mod_array::one_array($shops, ['id','name']);
            //获取国家
            $country_list = mod_area::list_data([
                'pid'      =>  0,
                'status'   =>  1,
                'order_by'  => ['id', 'asc'],
            ]);
            $country_options = mod_array::one_array($country_list, ['id', 'name']);

            return view('admin.store_add', [
                'status_options'    => mod_store::$status_map,
                'mobile_prefix_options' => $mobile_prefix_options,
                'shop_options' => $shop_options,
                'country_options' => $country_options,
                'img_thumb_with'    =>  mod_store::$img_thumb_with,
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
                return mod_common::error(mod_store::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("店舖修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_store::detail(['id' => $id]);
            //获取手机国码
            $mobile_prefix_options = mod_country::get_mobile_prefix();
            //获取商家列表
            $shops = mod_shop::list_data([
                'order_by' => ['create_time', 'asc']
            ]);
            $shop_options = mod_array::one_array($shops, ['id','name']);
            //获取国家
            $country_list = mod_area::list_data([
                'pid'      =>  0,
                'status'   =>  1,
                'order_by'  => ['id', 'asc'],
            ]);
            $country_options = mod_array::one_array($country_list, ['id', 'name']);
            //省份
            $province_list = mod_area::list_data([
                'pid'      =>  $row['country_id'],
                'status'   =>  1,
                'order_by'  => ['id', 'asc'],
            ]);
            $province_options = mod_array::one_array($province_list, ['id', 'name']);
            //城市
            $city_list = mod_area::list_data([
                'pid'      =>  $row['province_id'],
                'status'   =>  1,
                'order_by'  => ['id', 'asc'],
            ]);
            $city_options = mod_array::one_array($city_list, ['id', 'name']);
            //区或县
            $area_list = mod_area::list_data([
                'pid'      =>  $row['city_id'],
                'status'   =>  1,
                'order_by'  => ['id', 'asc'],
            ]);
            $area_options = mod_array::one_array($area_list, ['id', 'name']);

            return view('admin.store_edit', [
                'row'   =>  $row,
                'status_options'    => mod_store::$status_map,
                'mobile_prefix_options' => $mobile_prefix_options,
                'shop_options' => $shop_options,
                'country_options' => $country_options,
                'province_options' => $province_options,
                'city_options' => $city_options,
                'area_options' => $area_options,
                'img_thumb_with'    =>  mod_store::$img_thumb_with,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_store::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'shop_id'       => $request->input('shop_id', 0),
            'name'          => $request->input('name'),
            'country_id'    => $request->input('country_id', 0),
            'province_id'   => $request->input('province_id', 0),
            'city_id'       => $request->input('city_id', 0),
            'area_id'       => $request->input('area_id', 0),
            'address'       => $request->input('address'),
            'content'       => $request->input('content'),
            'pictures'      => $request->input('pictures', []),
            'contact'       => $request->input('contact'),
            'sex'           => $request->input('sex'),
            'phone_code'    => $request->input('phone_code'),
            'phone'         => $request->input('phone'),
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
        $row = mod_store::detail(['id' => $id]);

        return view('admin.store_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_store::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_store::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("店舖刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_store::change_status([
            'id'        => $id + [-1],
            'status'    => mod_store::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_store::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("店舖啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_store::change_status([
            'id'        => $id + [-1],
            'status'    => mod_store::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_store::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("店舖禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
