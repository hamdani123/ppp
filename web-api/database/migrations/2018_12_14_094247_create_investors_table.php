<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_perusahaan');
            $table->string('sektor')->nullable();
            $table->string('jenis')->nullable();
            $table->string('asal_zona')->nullable();
            $table->string('asal_negara')->nullable();
            $table->text('pemegang_saham')->nullable();
            $table->text('komisaris_utama')->nullable();
            $table->text('komisaris')->nullable();
            $table->text('direktur_utama')->nullable();
            $table->text('direksi')->nullable();
            $table->text('kantor_pusat')->nullable();
            $table->text('kantor_perwakilan_indonesia')->nullable();
            $table->text('proyek_di_indonesia')->nullable();
            $table->text('proyek_luar_indonesia')->nullable();
            $table->string('kontak_person')->nullable();
            $table->string('website')->nullable();
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
        Schema::dropIfExists('investors');
    }
}
