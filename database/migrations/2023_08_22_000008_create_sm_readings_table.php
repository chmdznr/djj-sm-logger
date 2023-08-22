<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmReadingsTable extends Migration
{
    public function up()
    {
        Schema::create('sm_readings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('spo_2', 15, 2);
            $table->integer('hr');
            $table->float('skin_temp', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
