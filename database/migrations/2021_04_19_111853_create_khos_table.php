<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma');
            $table->string('ten');
            $table->string('dia_chi');
            $table->string('loai');
            $table->integer('nhan_vien_ban_hang_id');
            $table->foreign('nhan_vien_ban_hang_id')->references('id')->on('users');
            $table->integer('thu_kho_id');
            $table->foreign('thu_kho_id')->references('id')->on('users');
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
        Schema::dropIfExists('khos');
    }
}
