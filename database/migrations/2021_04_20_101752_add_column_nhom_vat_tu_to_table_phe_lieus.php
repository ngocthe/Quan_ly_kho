<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNhomVatTuToTablePheLieus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phe_lieus', function (Blueprint $table) {
            $table->string('nhom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phe_lieus', function (Blueprint $table) {
            $table->dropColumn('nhom');
        });
    }
}
