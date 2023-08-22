<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSmReadingsTable extends Migration
{
    public function up()
    {
        Schema::table('sm_readings', function (Blueprint $table) {
            $table->unsignedBigInteger('responden_id')->nullable();
            $table->foreign('responden_id', 'responden_fk_8906507')->references('id')->on('respondens');
        });
    }
}
