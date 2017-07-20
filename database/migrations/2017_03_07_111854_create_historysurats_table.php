<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorysuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('historysurats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('no_surat')->unique();
            $table->string('perihal');
            $table->string('penerimaSurat');
            $table->integer('mahasiswa_id')->index();
            $table->integer('formatsurats_id')->index();
            $table->string('link_arsip_surat');
            $table->boolean('penandatanganan');
            $table->boolean('pengambilan');
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
