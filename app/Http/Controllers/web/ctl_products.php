<?php

namespace App\Http\Controllers\web;

use App\models\mod_common;
use App\models\mod_goods;
use App\models\mod_goods_cat;
use App\models\mod_goods_color;
use App\models\mod_redis;
use Illuminate\Http\Request;

class ctl_products extends Controller
{
    //是否使用缓存
    private static $is_use_cache = false;
    private static $cache_key = "product_cat";
    private static $detail_cache_key = "product:id:%s";

    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size  = $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no    = !empty($page_no) ? $page_no : 1;
        $name       = $request->input('keyword');
        $cat_id    = $request->input('cat_id');
        $type    = $request->input('type'); //new=新品 hot=熱門 rec=推薦
        $cat_id and $childs = mod_goods_cat::get_field_value([
            'fields' => 'childs',
            'id' => $cat_id
        ]);
        $cat_id = empty($childs) ? $cat_id : $cat_id.",".$childs;

        //獲取分類
        $cats = [];
        self::$is_use_cache and $cats = mod_redis::get(self::$cache_key);//獲取緩存數據
        if(empty($cats))
        {
            $cats = mod_goods_cat::list_data([
                'status'    =>  1,
                'order_by'  => ['create_time', 'asc']
            ]);
            self::$is_use_cache and mod_redis::set(self::$cache_key, $cats); //設置緩存
        }

        //獲取數據
        $rows = mod_goods::list_data([
            'name'      =>  $name,
            'cat_id'    =>  $cat_id,
            'type'      =>  $type,
            'status'    =>  1,
            'count'     =>  1,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {

        }

        return view('web.products_index', [
            'list'  =>  $rows['data'],
            'cats'  =>  mod_goods_cat::make_menu([]),
            'pages' =>  $pages,
        ]);
    }

    //詳情
    public function detail(Request $request)
    {
        $id  = $request->input('id');

        $row = [];
        self::$is_use_cache and $row = mod_redis::get(sprintf(self::$detail_cache_key, $id));
        if(empty($row))
        {
            $row = mod_goods::detail(['id' => $id]);
            self::$is_use_cache and mod_redis::set(sprintf(self::$detail_cache_key, $id), $row); //設置緩存
        }

        //獲取分類
        $cats = [];
        self::$is_use_cache and $cats = mod_redis::get(self::$cache_key);//獲取緩存數據
        if(empty($cats))
        {
            //獲取商品分類樹
            $cats = mod_goods_cat::make_menu([]);
            self::$is_use_cache and mod_redis::set(self::$cache_key, $cats); //設置緩存
        }
        //获取颜色
        $colors = mod_goods_color::list_data([
            'status'  => mod_goods_color::ENABLE,
            'order_by'  => ['create_time', 'asc']
        ]);
        return view('web.products_detail', [
            'product'   =>  $row,
            'cats'      =>  $cats,
            'colors'    =>  $colors,
        ]);
    }

    //搜寻
    public function search(Request $request)
    {
        return $this->index($request);
    }
}
