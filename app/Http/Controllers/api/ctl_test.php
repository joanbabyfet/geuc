<?php

namespace App\Http\Controllers\api;

use App\models\mod_common;
use App\models\mod_user;
use App\models\mod_wallet;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\mod_goods;
use App\models\mod_redis;

class ctl_test extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        if($request->isMethod('POST'))
        {
            //$user = mod_user::find($this->uid);
            //$user->deposit(1000); //充值
            //$user->deposit(200); //充值

            //$user->withdraw(5000);
            //$user->withdraw(500);
            //$user->forceWithdraw(350); //強制提現,額度不夠會造成負值
            //$user->withdraw(300, null, false);

            //$kos_wallert = mod_user::find('36f4fd67e66da73f76458ec2e4a49351');
            //$user->transfer($kos_wallert, 400);

            //echo mod_wallet::confirm(['id'=>3]); //id=流水id

            pr(request()->all());
        }
    }

    public function update_stock(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $id = $request->input('id');
            $count = $request->input('count');

            //走redis排他鎖,遇锁等待3秒 (最佳参数等待2秒,有效时间30秒,)
            if (!mod_redis::redis_lock('4a2cb500e771a1ae9fe0b001cd2e5105', 2, 30))
            {
                return mod_common::error('系统暂时忙碌中，请稍后重新下单，感谢您的理解', -3);
            }
            $status = mod_goods::update_stock([
                'id'    => $id,
                'count' => $count
            ]);
            mod_redis::redis_unlock('4a2cb500e771a1ae9fe0b001cd2e5105');

            //走数据库排他锁
            $status = mod_goods::update_stock([
                'id'    => $id,
                'count' => $count
            ]);

            if($status < 0)
            {
                return mod_common::error(mod_goods::get_err_msg($status), $status);
            }

            return mod_common::success([
                'id'    => $id
            ], '更新库存成功');
        }
    }
}
