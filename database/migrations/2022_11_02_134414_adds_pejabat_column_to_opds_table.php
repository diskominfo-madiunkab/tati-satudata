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
        Schema::table('opds', function (Blueprint $table) {
            $table->string('nip_penjabat')->nullable();
            $table->string('nama_penjabat')->nullable();
            $table->string('pangkat_penjabat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opds', function (Blueprint $table) {
            $table->dropColumn('nip_penjabat');
            $table->dropColumn('nama_penjabat');
            $table->dropColumn('pangkat_penjabat');
        });
    }
};
