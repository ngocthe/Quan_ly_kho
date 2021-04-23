<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhanVienBanHangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhan_vien_ban_hangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ten');
            $table->string('sdt')->nullable();
            $table->string('email')->nullable();
            $table->string('cmnd')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('nhan_vien_ban_hangs');
    }
}
