<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCreateBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhap_khos', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
        });
        Schema::table('xuat_khos', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
        });
        Schema::table('phan_loais', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
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
