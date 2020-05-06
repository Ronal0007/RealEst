<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->unique();
            $table->double('advanceFactor');
            $table->double('ramani');
            $table->double('hati');
            $table->double('usajiriFactor');
            $table->double('ushuruSubstraction');
            $table->double('ushuruFactor');
            $table->double('ushuruAddition');
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
        Schema::dropIfExists('constants');
    }
}
