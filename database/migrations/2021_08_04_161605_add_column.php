<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_nhap_khos', function (Blueprint $table) {
            $table->dropColumn('kho_id');
        });
        Schema::table('chi_tiet_nhap_khos', function (Blueprint $table) {
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
        Schema::table('chi_tiet_nhap_khos', function (Blueprint $table) {
            $table->dropColumn('kho_id');
        });
    }
}
