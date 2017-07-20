<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePesanansuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanansurats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('mahasiswa_id')->index();
            $table->integer('formatsurats_id')->index();
            $table->string('dataSurat');
            $table->string('penerimaSurat');
            $table->boolean('persetujuanDosenWali');
            $table->string('tglDosenWali');
            $table->boolean('persetujuanKaprodi');
            $table->string('tglKaprodi');
            $table->boolean('persetujuanWDII');
            $table->string('tglWDII');
            $table->boolean('persetujuanWDI');
            $table->string('tglWDI');
            $table->boolean('persetujuanDekan');
            $table->string('tglDekan');
            $table->integer('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesanansurats');
    }
}
