<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelineProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline_proyeks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_proyek');
            $table->string('tahapan')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('plan_tanggal_mulai')->nullable();
            $table->string('plan_tanggal_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('warna')->nullable();
            $table->string('actual_tanggal_mulai')->nullable();
            $table->string('actual_tanggal_selesai')->nullable();
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
        Schema::dropIfExists('timeline_proyeks');
    }
}
