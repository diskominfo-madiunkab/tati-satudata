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
        Schema::create('box_values', function (Blueprint $table) {
            $table->id();
            $table->text('judul');
            $table->decimal('ringkasan_nilai', 8, 2);
            $table->text('satuan');
            $table->text('logo');
            $table->integer('data_id');
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
        Schema::dropIfExists('box_values');
    }
};
