<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->char('id', 32)->default('');
            $table->tinyInteger("type")->default(1)->nullable()->comment('商品類型：1=實物商品 2=虛擬商品 3=電子卡密');
            $table->integer('cat_id')->default(0)->nullable()->comment('分類id');
            $table->char('store_id', 32)->default('')->nullable()->comment('所屬店舖id');
            $table->string('sn',20)->default('')->nullable()->comment('商品編號');
            $table->string('name',100)->default('')->nullable()->comment('商品名稱');
            $table->string('name_en',100)->default('')->nullable()->comment('商品英文名稱');
            $table->text("desc")->nullable()->comment('商品簡介');
            $table->text("desc_en")->nullable()->comment('商品英文簡介');
            $table->string('img',255)->default('')->nullable()->comment('商品大圖');
            $table->string('img_en',255)->default('')->nullable()->comment('商品大圖');
            $table->string('thumb',255)->default('')->nullable()->comment('商品縮圖');
            $table->string('thumb_en',255)->default('')->nullable()->comment('商品縮圖');
            $table->text("spec")->nullable()->comment('商品規格 json數據');
            $table->text("spec_en")->nullable()->comment('商品英文規格 json數據');
            $table->decimal("price", 15, 2)->default(0.00)->nullable()->unsigned()->comment('價格');
            $table->decimal("origin_price", 15, 2)->default(0.00)->nullable()->unsigned()->comment('原價');
            $table->string('currency_code',10)->default('')->nullable()->comment('幣種');
            $table->string('unit',5)->default('')->nullable()->comment('單位');
            $table->integer('stock')->default(0)->nullable()->comment("庫存");
            $table->integer('sold_num')->default(0)->nullable()->comment("已售數量");
            $table->integer('limit_buy')->default(0)->nullable()->comment("每人限購數量 0=不限");
            $table->integer('promotion_id')->default(0)->nullable()->comment("相關促銷id");
            $table->string('color',50)->default('')->nullable()->comment('顏色');
            $table->string('accessory',255)->default('')->nullable()->comment('配件');
            $table->tinyInteger("is_hot")->default(0)->nullable()->comment('是否热门商品：0=否 1=是');
            $table->tinyInteger("is_rec")->default(0)->nullable()->comment('是否推荐商品：0=否 1=是');
            $table->tinyInteger("is_new")->default(0)->nullable()->comment('是否新品：0=否 1=是');
            $table->smallInteger("sort")->default(0)->nullable()->comment('排序：数字小的排前面');
            $table->tinyInteger("status")->default(1)->nullable()->comment('状态：0=禁用 1=启用');
            $table->integer('start_time')->default(0)->nullable()->comment("上架時間");
            $table->integer('end_time')->default(0)->nullable()->comment("下架時間");
            $table->integer('create_time')->default(0)->nullable()->comment("創建時間");
            $table->char('create_user', 32)->default('0')->nullable()->comment("創建人");
            $table->integer('update_time')->default(0)->nullable()->comment("修改時間");
            $table->char('update_user', 32)->default('0')->nullable()->comment("修改人");
            $table->integer('delete_time')->default(0)->nullable()->comment("刪除時間");
            $table->char('delete_user', 32)->default('0')->nullable()->comment("刪除人");
            $table->primary(['id']);
        });
        $table = DB::getTablePrefix().'goods';
        DB::statement("ALTER TABLE `{$table}` comment'商品表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
