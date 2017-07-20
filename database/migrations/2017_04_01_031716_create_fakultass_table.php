<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakultassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fakultass', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('kode_fakultas')->unique();
            $table->string('nama_fakultas');
            $table->integer('dekan')->index();
            $table->integer('wdI')->index();
            $table->integer('wdII')->index();
            $table->integer('wdIII')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fakultass');
    }
}
