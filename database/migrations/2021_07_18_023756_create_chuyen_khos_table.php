<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChuyenKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chuyen_khos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ngay');
            $table->string('so_phieu');
            $table->integer('nguoi_tao_phieu');
            $table->integer('tu_kho_id');
            $table->integer('den_kho_id');
            $table->string('ghi_chu')->nullable();

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
        Schema::dropIfExists('chuyen_khos');
    }
}
