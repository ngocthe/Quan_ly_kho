<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDauGiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dau_gias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma');
            $table->string('ma_san_pham')->nullable();
            $table->string('ten_san_pham');
            $table->json('hinh_anhs')->nullable();
            $table->dateTime('bat_dau');
            $table->dateTime('ket_thuc');
            $table->text('mo_ta')->nullable();
            $table->text('chi_tiet')->nullable();
            $table->text('so_luong_ban')->nullable();
            $table->decimal('gia_khoi_diem',18,0)->nullable();
            $table->text('dvt')->nullable();
            $table->string('trang_thai')->default('chua_dien_ra');
            $table->timestamps();
        });

        Schema::create('khach_hang_dau_gias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma');
            $table->integer('khach_hang_id');
            $table->string('khach_hang');
            $table->integer('dau_gia_id'); 
            $table->decimal('gia',18,0)->nullable();
            $table->boolean('thang')->default(false);
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
        Schema::dropIfExists('dau_gias');
        Schema::dropIfExists('khach_hang_dau_gias');

    }
}
