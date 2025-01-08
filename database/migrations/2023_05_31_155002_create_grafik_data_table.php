<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grafik_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_data');
            $table->bigInteger('axis_x');
            $table->bigInteger('axis_y');
            $table->bigInteger('kategori');
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
        Schema::dropIfExists('grafik_data');
    }
};
