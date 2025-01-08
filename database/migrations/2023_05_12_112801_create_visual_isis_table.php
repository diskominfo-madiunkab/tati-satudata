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
        Schema::create('visual_isis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_header');
            $table->longText('isi');
            $table->bigInteger('urutan_kebawah');
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
        Schema::dropIfExists('visual_isis');
    }
};
