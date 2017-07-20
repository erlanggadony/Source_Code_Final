<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDosensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dosens', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nik')->unique();
            $table->string('nama_dosen');
            $table->string('foto_dosen');
            $table->integer('jurusan_id')->index();
            $table->integer('fakultas_id')->index();
            $table->string('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
