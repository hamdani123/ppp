<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalisisResikosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analisis_resikos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_proyek');
            $table->integer('id_penjaminan');
            $table->string('jenis')->nullable();
            $table->string('penjamin')->nullable();
            $table->double('nilai', 20, 4)->default(0);
            $table->string('mitigasi')->nullable();
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
        Schema::dropIfExists('analisis_resikos');
    }
}
