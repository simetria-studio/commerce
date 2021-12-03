<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('payment_id')->nullable();
            $table->string('issuer_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('payment_type_id')->nullable();
            $table->string('status')->nullable();
            $table->string('status_detail')->nullable();
            $table->string('currency_id')->nullable();
            $table->string('collector_id')->nullable();
            $table->float('net_received_amount')->nullable();
            $table->float('total_paid_amount')->nullable();
            $table->string('installments')->nullable();
            $table->float('installment_amount')->nullable();
            $table->float('rate_mp')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_cnpj_cpf')->nullable();
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
        Schema::dropIfExists('payment_orders');
    }
}
