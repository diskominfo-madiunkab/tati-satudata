<?php

use App\Models\Data;
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
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Data::class, 'data_id')->constrained('data')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->integer('size');
            $table->longText('path');
            $table->string('resource_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
