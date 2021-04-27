<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiTietXuatKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_xuat_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('xuat_kho_id')->nullable();
            $table->string('dvt')->nullable();
            $table->integer('phe_lieu_id')->nullable();
            $table->double('so_luong_de_xuat')->nullable();
            $table->double('so_luong_thuc_te')->nullable();
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
        Schema::dropIfExists('chi_tiet_xuat_khos');
    }
}
