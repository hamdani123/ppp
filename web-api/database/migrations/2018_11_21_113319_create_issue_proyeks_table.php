<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_proyeks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_proyek');
            $table->integer('id_tahapan');
            $table->string('tipe')->nullable();
            $table->string('deskripsi');
            $table->string('status')->nullable();
            $table->string('resolved_date')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('tindak_lanjut')->nullable();
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
        Schema::dropIfExists('issue_proyeks');
    }
}
