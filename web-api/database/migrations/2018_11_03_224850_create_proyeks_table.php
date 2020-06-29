<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyeks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_proyek');
            $table->string('sektor_proyek')->nullable();
            $table->text('lingkup_proyek')->nullable();
            $table->string('lokasi_proyek')->nullable();
            $table->string('pjpk_proyek')->nullable();
            $table->string('tipe_kpbu')->nullable();
            $table->string('badan_usaha_penyiapan')->nullable();
            $table->string('fasilitator')->nullable();
            $table->string('image')->nullable();
            $table->string('skema_pengembalian_investasi')->nullable();
            $table->double('dukungan_vgf', 20, 4)->nullable();
            $table->string('dukungan_konstruksi')->nullable();
            $table->string('dukungan_lain')->nullable();
            $table->boolean('jaminan_proyek')->nullable();
            $table->double('nilai_investasi', 20, 4)->nullable();
            $table->string('financial_npv')->nullable();
            $table->string('financial_irr')->nullable();
            $table->string('economical_irr')->nullable();
            $table->string('masa_konsesi')->nullable();
            $table->double('kurs', 20, 4)->nullable();
            $table->string('mata_uang')->nullable();
            $table->string('financier')->nullable();
            $table->string('kode_proyek')->nullable();
            $table->string('struktur_kontrak_kpbu')->nullable();
            $table->integer('posisi_timeline')->default(0);
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
        Schema::dropIfExists('proyeks');
    }
}
