<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_cnpj_cpf')->nullable();
            $table->string('birth_date')->nullable();
            $table->float('total_value')->nullable();
            $table->float('cost_freight')->nullable();
            $table->float('product_value')->nullable();
            $table->string('pay')->nullable();
            $table->float('discount')->nullable();
            $table->float('coupon_value')->nullable();
            $table->string('coupon')->nullable();
            $table->string('active')->default('S');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
