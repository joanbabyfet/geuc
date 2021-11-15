<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_permission_groups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = date('Y-m-d H:i:s');

        $fields = [
            'name',
            'created_at',
        ];

        $rows = [
            ['用戶管理', $created_at],
            ['用戶組别', $created_at],
            ['權限管理', $created_at],
            ['權限組别', $created_at],
            ['菜單管理', $created_at],
            ['日志管理', $created_at],
            ['會員管理', $created_at],
            ['會員等級', $created_at],
            ['H5管理', $created_at],
            ['報表管理', $created_at],
            ['新聞管理', $created_at],
            ['采集新聞', $created_at],
            ['采集網站', $created_at],
            ['頻道管理', $created_at],
            ['國家設置', $created_at],
            ['文章管理', $created_at],
            ['緩存管理', $created_at],
            ['配置管理', $created_at],
            ['商家管理', $created_at],
            ['店舖管理', $created_at],
            ['商品管理', $created_at],
            ['商品分類', $created_at],
            ['商品顏色', $created_at],
            ['短信營銷', $created_at],
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
        //DB::table('permission_groups')->truncate(); //干掉所有数据,并将自增重設为0
        DB::table('permission_groups')->insert($insert_data);
    }
}
