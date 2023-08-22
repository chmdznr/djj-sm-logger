<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIotReadingsTable extends Migration
{
    public function up()
    {
        Schema::create('iot_readings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fetal_hr');
            $table->integer('resp_count');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
