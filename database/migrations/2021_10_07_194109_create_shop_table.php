<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop', function (Blueprint $table) {
            $table->char('id', 32)->default('');
            $table->string('name',50)->default('')->nullable()->comment('商家/公司名稱');
            $table->string('username', 20)->default('')->nullable()->comment("商家帐号");
            $table->string('password', 60)->default('')->nullable()->comment("商家密码");
            $table->integer('country_id')->default(0)->nullable()->comment('國家id');
            $table->integer('province_id')->default(0)->nullable()->comment('省份id');
            $table->integer('city_id')->default(0)->nullable()->comment('城市id');
            $table->integer('area_id')->default(0)->nullable()->comment('區/縣id');
            $table->string('address',100)->default('')->nullable()->comment('商家地址');
            $table->string('license',255)->default('')->nullable()->comment('經營證件照');
            $table->string('contact',30)->default('')->nullable()->comment('聯絡人');
            $table->tinyInteger('sex')->default(1)->nullable()->comment('聯絡人性別');
            $table->string('phone_code',5)->default('')->nullable()->comment('電話國碼');
            $table->string('phone',20)->default('')->nullable()->comment('聯絡電話');
            $table->tinyInteger("status")->default(0)->nullable()->comment('状态：0=待審核 1=啟用 -1=禁用 -2=凍結 -3=駁回');
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
            $table->primary(['id']);
        });
        $table = DB::getTablePrefix().'shop';
        DB::statement("ALTER TABLE `{$table}` comment'商家表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop');
    }
}
