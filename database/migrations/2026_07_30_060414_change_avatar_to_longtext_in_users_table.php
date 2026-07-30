<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom 'avatar' dari VARCHAR(255) (path file) menjadi LONGTEXT
     * supaya bisa menyimpan gambar dalam bentuk base64 langsung di database,
     * tanpa perlu file fisik / folder storage sama sekali.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY avatar LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY avatar VARCHAR(255) NULL');
    }
};