<?php

namespace App\Http\Controllers\admin;

use App\models\mod_area;
use App\models\mod_goods_cat;
use App\models\mod_navigation;
use App\models\mod_req;
use Illuminate\Http\Request;
use App\models\mod_google;
use App\models\mod_common;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * 公共管理
 *
 * Class ctl_common
 * @package App\Http\Controllers\admin
 */
class ctl_common extends Controller
{
    /**
     * 翻译成中文繁体，默认为繁体
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        $content  = $request->input('content', '');
        $out_lang  = $request->input('out_lang') ?? 'zh-TW';

        $ret = mod_google::translate([
            'content' => $content,
            'out_lang' => $out_lang,
        ]);
        if($ret < 0)
        {
            return mod_common::error(mod_google::get_err_msg($ret), $ret);
        }
        return mod_common::success($ret);
    }

    /**
     * 获取csrf令牌,测试用
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_csrf_token()
    {
        return mod_common::success([
            'csrf_token' => csrf_token()
        ]);
    }

    //测试workerman客户端发送信息
    public function wk_send(Request $request)
    {
        if($request->isMethod('POST'))
        {

        }
        else
        {
            return view('admin.common_wk_send', [
                //'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkuc2llbmVyZ3kubG9jYWxcL2xvZ2luIiwiaWF0IjoxNjMyODAyMTIzLCJleHAiOjE2MzI4MDU3MjMsIm5iZiI6MTYzMjgwMjEyMywianRpIjoienR0MFJyY04ybXlVYkI4UiIsInN1YiI6MiwicHJ2IjoiOTIyNDBmZmI4YTExMTRjODAzZWNiOTMyZmI3MjlhY2UwOGVkZmMzNSJ9.RHEbZd1T_7lxUkKOXW9kiI9nEya25eDMRC-Z5kyrOO4',
                'token' => $request->session()->getId(),
            ]);
        }
    }

    //获取所有区域下级列表
    public function ajax_get_area(Request $request)
    {
        $pid = $request->input('pid', 0);

        //獲取數據
        $rows = mod_area::get_options($pid);

        return mod_common::success($rows);
    }

    //获取商品分类选项
    public function ajax_get_goods_cat(Request $request)
    {
        $ret = '[]';
        $store_id = $request->input('store_id', '');
        $id = $request->input('id', 0);
        if(empty($store_id)) return $ret;

        //獲取數據
        $rows = mod_goods_cat::get_tree([
            'id'        => $id,
            'store_id'  => $store_id,
            'status'    => mod_goods_cat::ENABLE,
            'order_by'  => ['create_time', 'asc']
        ]);
        return empty($rows) ? $ret : mod_common::array_to_str($rows);
    }

    //获取菜单选项
    public function ajax_get_navigation(Request $request)
    {
        $ret = '[]';
        $id = $request->input('id', 0);

        //獲取數據
        $rows = mod_navigation::get_tree([
            'id'            => $id,
            'guard'         => $this->guard,
            'type'          => 'admin',
            'order_by'      => ['sort', 'asc'],
        ]);
        return empty($rows) ? $ret : mod_common::array_to_str($rows);
    }
}
