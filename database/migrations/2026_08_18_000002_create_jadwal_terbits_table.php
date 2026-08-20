<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jadwal_terbits')) {
            Schema::create('jadwal_terbits', function (Blueprint $table) {
                $table->id();
                $table->string('judul_buku');
                $table->string('penyusun')->nullable();
                $table->year('tahun')->nullable();
                $table->date('rencana_terbit')->nullable();
                $table->string('frekuensi_terbit')->default('Tahunan');
                $table->string('status_terbit')->default('Direncanakan'); // Direncanakan, Proses Penyusunan, Terbit
                $table->string('file_pdf')->nullable();
                $table->string('cover')->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_terbits');
    }
};
