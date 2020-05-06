<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_numbers', function (Blueprint $table) {
            $table->string('number')->primary();
            $table->integer('customer_id');
            $table->integer('plot_id');
            $table->integer('status_id')->default(2);
            $table->integer('payment_period_id');
            $table->integer('constant_id');
            $table->string('jijiControl')->nullable();
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_numbers');
    }
}
