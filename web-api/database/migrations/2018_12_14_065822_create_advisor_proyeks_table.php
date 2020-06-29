<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvisorProyeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisor_proyeks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_advisor');
            $table->integer('id_proyek');
            $table->string('benefit')->nullable();
            $table->text('tenaga_ahli')->nullable();
            $table->string('tahun_pelaksana')->nullable();
            $table->string('sumber_dana')->nullable();
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
        Schema::dropIfExists('advisor_proyeks');
    }
}
