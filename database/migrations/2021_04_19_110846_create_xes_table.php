<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bks');
            $table->string('dong_xe')->nullable();
            $table->string('chu_xe')->nullable();
            $table->string('lai_xe')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xes');
    }
}
