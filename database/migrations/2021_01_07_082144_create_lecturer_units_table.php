<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturerUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturer_units', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lecturer_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->timestamps();

            $table->foreign('lecturer_id')
                ->references('id')
                ->on('users');

            $table->foreign('unit_id')
                ->references('id')
                ->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturer_units');
    }
}
