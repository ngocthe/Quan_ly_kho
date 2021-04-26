<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietPhanLoaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_phan_loais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('phe_lieu_id')->nullable();
            $table->integer('phan_loai_id')->nullable();
            $table->integer('kho_id')->nullable();
            $table->double('so_luong')->nullable();
            $table->string('dvt')->nullable();
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
        Schema::dropIfExists('chi_tiet_phan_loais');
    }
}
