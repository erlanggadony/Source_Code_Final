<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormatsuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('formatsurats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('idFormatSurat')->unique();
            $table->string('jenis_surat');
            $table->string('keterangan');
            $table->string('link_format_surat');
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
