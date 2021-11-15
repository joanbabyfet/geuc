<?php

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent as ColumnDefinition;

class CreateTransactionsTable extends Migration
{

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->morphs('payable'); //2021-10-10修改
            $name = 'payable';
            $table->string("{$name}_type");
            $table->char("{$name}_id", 32)->default('');
            $table->index(["{$name}_type", "{$name}_id"], null);

            $table->enum('type', ['deposit', 'withdraw'])->index();
            $table->decimal('amount', 64, 0);
            $table->boolean('confirmed');
            $this->json($table, 'meta')->nullable();
            $table->uuid('uuid')->unique()->comment('单号');
            $table->timestamps();

            $table->index(['payable_type', 'payable_id', 'type'], 'payable_type_ind');
            $table->index(['payable_type', 'payable_id', 'confirmed'], 'payable_confirmed_ind');
            $table->index(['payable_type', 'payable_id', 'type', 'confirmed'], 'payable_type_confirmed_ind');
        });
        $table = DB::getTablePrefix().$this->table();
        DB::statement("ALTER TABLE `{$table}` comment'流水表'"); // 表注释
    }

    /**
     * @param Blueprint $table
     * @param string $column
     * @return ColumnDefinition
     */
    public function json(Blueprint $table, string $column): ColumnDefinition
    {
        $conn = DB::connection();
        if ($conn instanceof MySqlConnection || $conn instanceof PostgresConnection) {
            $pdo = $conn->getPdo();
            try {
                $sql = 'SELECT JSON_EXTRACT(\'[10, 20, [30, 40]]\', \'$[1]\');';
                $prepare = $pdo->prepare($sql);
                $prepare->fetch();
            } catch (\Throwable $throwable) {
                return $table->text($column);
            }
        }

        return $table->json($column);
    }

    /**
     * @return string
     */
    protected function table(): string
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
