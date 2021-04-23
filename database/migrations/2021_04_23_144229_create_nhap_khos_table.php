<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhapKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhap_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ngay');
            $table->integer('ca')->default(1);
            $table->integer('xe_id')->nullable();
            $table->integer('khach_hang_id')->nullable();
            $table->integer('kho_id')->nullable();
            $table->integer('tai_khoan_no_id')->nullable();
            $table->integer('tai_khoan_co_id')->nullable();
            $table->integer('status_id')->nullable();
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
        Schema::dropIfExists('nhap_khos');
    }
}
