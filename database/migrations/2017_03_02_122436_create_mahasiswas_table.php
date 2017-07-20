<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nirm')->unique();
            $table->string('npm')->unique();
            $table->string('nama_mahasiswa');
            $table->integer('jurusan_id')->index();
            $table->integer('fakultas_id')->index();
            $table->integer('angkatan');
            $table->string('kota_lahir');
            $table->date('tanggal_lahir');
            $table->string('foto_mahasiswa');
            $table->integer('dosen_id')->index();
            $table->string('username');
            $table->string('kewarganegaraan');
            $table->string('semester');
            $table->string('thnAlkademik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}
