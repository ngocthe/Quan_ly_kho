<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhachHangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma');
            $table->string('ma_misa');
            $table->string('ten');
            $table->text('dia_chi')->nullable();
            $table->text('email')->nullable();
            $table->text('sdt')->nullable();
            $table->integer('khu_vuc_id')->nullable();
            $table->text('khu_vuc')->nullable();
            $table->integer('father_id')->nullable();
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
        Schema::dropIfExists('khach_hangs');
    }
}
