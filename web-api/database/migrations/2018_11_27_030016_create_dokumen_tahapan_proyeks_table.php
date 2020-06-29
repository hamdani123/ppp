<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDokumenTahapanProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_tahapan_proyeks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_proyek');
            $table->integer('id_tahapan');
            $table->string('nama_dokumen');
            $table->string('jenis_dokumen');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('dokumen_tahapan_proyeks');
    }
}
