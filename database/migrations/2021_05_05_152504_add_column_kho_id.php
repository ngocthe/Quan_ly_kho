<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKhoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhan_vien_ban_hangs', function (Blueprint $table) {
            $table->integer('kho_id')->nullable();
        });
        Schema::table('thu_khos', function (Blueprint $table) {
            $table->integer('kho_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
