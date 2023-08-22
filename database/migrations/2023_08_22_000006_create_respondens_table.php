<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespondensTable extends Migration
{
    public function up()
    {
        Schema::create('respondens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('kode');
            $table->integer('usia');
            $table->string('his_adekuat')->nullable();
            $table->string('pergerakan')->nullable();
            $table->integer('paritas')->nullable();
            $table->integer('kardiotokografi')->nullable();
            $table->longText('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
