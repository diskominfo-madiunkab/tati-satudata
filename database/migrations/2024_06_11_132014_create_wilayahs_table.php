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
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->text('icon');
            $table->text('jml_data');
            $table->text('narasi_data');
            $table->text('narasi_1')->nullable();
            $table->text('jml_narasi_1')->nullable();
            $table->text('narasi_2')->nullable();
            $table->text('jml_narasi_2')->nullable();
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
        Schema::dropIfExists('wilayahs');
    }
};
