<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXuatKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xuat_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ngay');
            $table->string('so_phieu');
            $table->integer('tk_co_id')->nullable();
            $table->integer('tk_no_id')->nullable();
            $table->integer('doi_tac_id')->nullable();
            $table->text('ly_do')->nullable();
            $table->integer('kho_id');
            $table->boolean('thu_kho_xac_nhan')->default(false);
            $table->integer('nvbh_id')->nullable();
            $table->integer('ke_toan_id')->nullable();
            $table->integer('bao_ve_id')->nullable();
            $table->integer('xe_id')->nullable();
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
        Schema::dropIfExists('xuat_khos');
    }
}
