<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTahapansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_tahapans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('action');
            $table->integer('id_tahapan');
            $table->integer('id_proyek');
            $table->string('nama_tahapan')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('nama_sub_kegiatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('tanggal_info')->nullable();
            $table->string('sumber_info')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('pihak_terkait')->nullable();
            $table->string('target_penyelesaian')->nullable();
            $table->string('status')->nullable();
            $table->string('tanggal_selesai')->nullable();
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
        Schema::dropIfExists('history_tahapans');
    }
}
