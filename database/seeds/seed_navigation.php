<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_navigation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            'parent_id',
            'level',
            'icon',
            'uri',
            'is_link',
            'permission_name',
            'name',
            'type',
            'guard_name',
        ];

        $rows = [
            [0, 0, '', '', 0, '', '常用', 'admin', 'admin'],
            [0, 0, '', '', 0, '', '報表', 'admin', 'admin'],
            [0, 0, '', '', 0, '', '系統', 'admin', 'admin'],
            [1, 1, '', '/member', 0, 'admin.member.index', '會員管理', 'admin', 'admin'],
            [1, 1, '', '/member_level', 0, 'admin.member_level.index', '會員等級', 'admin', 'admin'],
            [1, 1, '', '/member_login', 0, 'admin.member_login.index', '登入日志', 'admin', 'admin'],
            [1, 1, '', '/news', 0, 'admin.news.index', '新聞管理', 'admin', 'admin'],
            [1, 1, '', '/news_cat', 0, 'admin.news_cat.index', '新聞分類', 'admin', 'admin'],
            [1, 1, '', '/country', 0, 'admin.news.index', '國家設置', 'admin', 'admin'],
            [1, 1, '', '/shop', 0, 'admin.shop.index', '商家管理', 'admin', 'admin'],
            [1, 1, '', '/store', 0, 'admin.store.index', '店舖管理', 'admin', 'admin'],
            [1, 1, '', '/goods', 0, 'admin.goods.index', '商品管理', 'admin', 'admin'],
            [1, 1, '', '/goods_cat', 0, 'admin.goods_cat.index', '商品分類', 'admin', 'admin'],
            [1, 1, '', '/goods_color', 0, 'admin.goods_color.index', '商品顏色', 'admin', 'admin'],
            [1, 1, '', '/h5', 0, 'admin.h5.index', 'H5管理', 'admin', 'admin'],
            [2, 1, '', '/report/member_increase_data', 0, 'admin.report.member_increase_data', '會員增長數', 'admin', 'admin'],
            [3, 1, '', '/admin_user', 0, 'admin.admin_user.index', '用戶管理', 'admin', 'admin'],
            [3, 1, '', '/role', 0, 'admin.role.index', '用戶組別', 'admin', 'admin'],
            [3, 1, '', '/navigation', 0, 'admin.navigation.index', '菜單管理', 'admin', 'admin'],
            [3, 1, '', '/permission', 0, 'admin.permission.index', '權限管理', 'admin', 'admin'],
            [3, 1, '', '/permission_group', 0, 'admin.permission_group.index', '權限組別', 'admin', 'admin'],
            [3, 1, '', '/admin_user_oplog', 0, 'admin.admin_user_oplog.index', '操作日志', 'admin', 'admin'],
            [3, 1, '', '/admin_user_login', 0, 'admin.admin_user_login.index', '登入日志', 'admin', 'admin'],
            [3, 1, '', '/api_req_log', 0, 'admin.api_req_log.index', '訪問日志', 'admin', 'admin'],
            [3, 1, '', '/config', 0, 'admin.config.index', '配置管理', 'admin', 'admin'],
            [3, 1, '', '/redis_keys', 0, 'admin.cache.redis_keys', 'Redis鍵值管理', 'admin', 'admin'],
            [3, 1, '', '/redis_info', 0, 'admin.cache.redis_info', 'Redis服務器信息', 'admin', 'admin'],
        ];

        $insert_data = [];
        foreach ($rows as $row)
        {
            $item = [];
            foreach ($fields as $k => $field)
            {
                $item[$field] = $row[$k];
            }
            $insert_data[] = $item;
        }
        //DB::table('navigation')->truncate(); //干掉所有数据,并将自增重設为0
        DB::table('navigation')->insert($insert_data);
    }
}
