<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietChuyenKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_chuyen_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('chuyen_kho_id')->nullable();
            $table->string('dvt')->nullable();
            $table->integer('phe_lieu_id')->nullable();
            $table->double('so_luong')->nullable();
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
        Schema::dropIfExists('chi_tiet_chuyen_khos');
    }
}
