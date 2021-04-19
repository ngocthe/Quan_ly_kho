<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kho_id');
            $table->foreign('kho_id')->references('id')->on('khos');
            $table->integer('phe_lieu_id');
            $table->foreign('phe_lieu_id')->references('id')->on('phe_lieus');
            $table->string('dvt')->nullable();
            $table->double('khoi_luong')->default(0);
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
        Schema::dropIfExists('chi_tiet_khos');
    }
}
