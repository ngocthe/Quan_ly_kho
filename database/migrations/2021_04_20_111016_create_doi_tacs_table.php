<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoiTacsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doi_tacs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ma');
            $table->string('ma_misa');
            $table->string('ten');
            $table->string('ma_so_thue')->nullable();
            $table->text('dia_chi')->nullable();
            $table->text('email')->nullable();
            $table->text('sdt')->nullable();
            $table->integer('khu_vuc_id')->nullable();
            $table->text('khu_vuc')->nullable();
            $table->timestamps();
        });

        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->string('loai')->nullable();
            $table->string('ma_so_thue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doi_tacs');
    }
}
