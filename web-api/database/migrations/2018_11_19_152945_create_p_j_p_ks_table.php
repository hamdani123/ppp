<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePJPKsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_j_p_ks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lokasi')->nullable();
            $table->string('pjpk');
            $table->string('pelaksana_tugas')->nullable();
            $table->string('simpul_kpbu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_j_p_ks');
    }
}
