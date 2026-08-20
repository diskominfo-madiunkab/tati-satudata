<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('regulasis')) {
            Schema::create('regulasis', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('nomor')->nullable();
                $table->year('tahun')->nullable();
                $table->text('tentang')->nullable();
                $table->string('file_dokumen')->nullable();
                $table->string('kategori')->default('Peraturan Bupati');
                $table->string('status')->default('Berlaku');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('regulasis');
    }
};
