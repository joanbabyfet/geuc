<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGoodsColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_color', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->default('')->nullable()->comment('名稱');
            $table->string('img',255)->default('')->nullable()->comment('圖片');
            $table->tinyInteger("status")->default(1)->nullable()->comment('状态：0=禁用 1=启用');
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
        });
        $table = DB::getTablePrefix().'goods_color';
        DB::statement("ALTER TABLE `{$table}` comment'商品顏色表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_color');
    }
}
