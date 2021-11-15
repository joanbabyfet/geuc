<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGoodsCatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_cat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0)->nullable()->comment('上級id');
            $table->tinyInteger("level")->default(0)->nullable()->comment('级别：0=国家，1=省份，2=城市');
            $table->string("childs")->default('')->nullable()->comment('该分类下所有子分类以,分隔');
            $table->char('store_id', 32)->default('')->nullable()->comment('所屬店舖id');
            $table->string('name',50)->default('')->nullable()->comment('分類名稱');
            $table->string('name_en',50)->default('')->nullable()->comment('分類英文名稱');
            $table->text("desc")->nullable()->comment('描述');
            $table->text("desc_en")->nullable()->comment('英文描述');
            $table->smallInteger("sort")->default(0)->nullable()->comment('排序：数字小的排前面');
            $table->tinyInteger("status")->default(1)->nullable()->comment('状态：0=禁用 1=启用');
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
        });
        $table = DB::getTablePrefix().'goods_cat';
        DB::statement("ALTER TABLE `{$table}` comment'商品分類表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_cat');
    }
}
