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
        Schema::create('metadata_variabel', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Data::class, 'data_id')->constrained('data')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama');
            $table->string('alias')->nullable();
            $table->longText('konsep')->nullable();
            $table->longText('definisi')->nullable();
            $table->string('referensi_pemilihan')->nullable();
            $table->string('referensi_waktu')->nullable();
            $table->string('tipe_data')->nullable();
            $table->longText('klasifikasi_isian')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('satuan')->nullable();
            $table->longText('aturan_validasi')->nullable();
            $table->longText('kalimat_pertanyaan')->nullable();
            $table->boolean('umum')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_variabel');
    }
};
