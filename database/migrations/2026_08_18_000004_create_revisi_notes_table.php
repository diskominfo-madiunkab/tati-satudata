<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('revisi_notes')) {
            Schema::create('revisi_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('data_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('tahapan')->default('pemeriksaan'); // perencanaan, pengumpulan, pemeriksaan
                $table->text('catatan');
                $table->string('status_sebelumnya')->nullable();
                $table->string('status_sesudahnya')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_notes');
    }
};
