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
        Schema::table('users', function (Blueprint $table) {
            // 1. Tambah kolom google_id setelah email (boleh kosong/nullable)
            $table->string('google_id')->nullable()->after('email');
            
            // 2. Ubah kolom password jadi boleh kosong (karena login Google tidak pakai password)
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom google_id jika migration dibatalkan
            $table->dropColumn('google_id');
            
            // Kembalikan password menjadi wajib diisi (opsional)
            $table->string('password')->nullable(false)->change();
        });
    }
};