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
        Schema::create('visual_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_data');
            $table->bigInteger('id_berkas');
            $table->longText('tahun');
            $table->bigInteger('nilai');
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
        Schema::dropIfExists('visual_data');
    }
};
