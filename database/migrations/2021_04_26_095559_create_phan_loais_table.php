<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhanLoaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phan_loais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ngay');
            $table->string('so_phieu')->nullable();
            $table->string('nguoi_can')->nullable();
            $table->integer('khach_hang_id');
            $table->integer('phe_lieu_id');
            $table->integer('kho_id');
            $table->string('noi_dung')->nullable();
            $table->double('so_luong')->nullable();
            $table->timestamps();
        });
        Schema::table('nhap_khos', function (Blueprint $table) {
            $table->string('so_phieu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phan_loais');
    }
}
