<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standar_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Data::class, 'data_id')->constrained('data')->cascadeOnUpdate()->cascadeOnDelete();
            $table->longText('konsep')->nullable();
            $table->longText('definisi')->nullable();
            $table->longText('klasifikasi')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_data');
    }
};
