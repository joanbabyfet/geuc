<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_admin_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            'id',
            'username',
            'password',
            'realname',
            'email',
            'phone_code',
            'phone',
            'status',
            'safe_ips',
            'is_first_login',
            'is_audit',
            'session_expire',
            'session_id',
            'reg_ip',
            'login_time',
            'login_ip',
            'remember_token',
            'api_token',
            'create_time',
            'create_user'
        ];

        $rows = [
            ['1', 'admin', bcrypt('Bb123456'), '管理員', 'admin@gmail.com', '', '', 1, '', 1, 0, 1440, '', '', 0, '', '', '', time(), '0'],
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
        DB::table('admin_users')->insert($insert_data); //走批量插入
    }
}
