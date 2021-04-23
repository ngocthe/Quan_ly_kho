<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietNhapKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_nhap_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nhap_kho_id')->default(1);
            $table->integer('phe_lieu_id')->nullable();
            $table->integer('dvt')->nullable();
            $table->double('so_luong_thuc_te')->nullable();
            $table->double('so_luong_chung_tu')->nullable();
            $table->decimal('don_gia',18,0)->nullable();
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
        Schema::dropIfExists('chi_tiet_nhap_khos');
    }
}
