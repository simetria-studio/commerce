<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_banks', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->float('balance_available')->nulllable()->default(0);
            $table->float('balance_withdrawn')->nulllable()->default(0);
            $table->float('accumulated_total')->nulllable()->default(0);
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
        Schema::dropIfExists('affiliate_banks');
    }
}
