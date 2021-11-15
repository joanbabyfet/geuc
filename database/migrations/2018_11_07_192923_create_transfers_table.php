<?php

use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Transfer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->morphs('from'); //2021-10-10修改
            //$table->morphs('to');
            $name = 'from';
            $table->string("{$name}_type");
            $table->char("{$name}_id", 32)->default('');
            $table->index(["{$name}_type", "{$name}_id"], null);
            $name = 'to';
            $table->string("{$name}_type");
            $table->char("{$name}_id", 32)->default('');
            $table->index(["{$name}_type", "{$name}_id"], null);

            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('withdraw_id');
            $table->uuid('uuid')->unique()->comment('单号');
            $table->timestamps();

            $table->foreign('deposit_id')
                ->references('id')
                ->on($this->transactionTable())
                ->onDelete('cascade');

            $table->foreign('withdraw_id')
                ->references('id')
                ->on($this->transactionTable())
                ->onDelete('cascade');
        });
        $table = DB::getTablePrefix().$this->table();
        DB::statement("ALTER TABLE `{$table}` comment'錢包轉帳表'"); // 表注释
    }

    /**
     * @return string
     */
    protected function table(): string
    {
        return (new Transfer())->getTable();
    }

    /**
     * @return string
     */
    protected function transactionTable(): string
    {
        return (new Transaction())->getTable();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::drop($this->table());
    }

}
