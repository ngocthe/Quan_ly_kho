<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnDuyetToPhanLoais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_phan_loais', function (Blueprint $table) {
            $table->integer('nguoi_duyet_id')->nullable();
            $table->boolean('duyet')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phan_loais', function (Blueprint $table) {
            //
        });
    }
}
