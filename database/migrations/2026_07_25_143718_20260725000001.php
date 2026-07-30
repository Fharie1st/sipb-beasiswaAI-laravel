<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom baru yang dibutuhkan alur chatbot (prodi, sks,
     * tanggungan, prediction, accuracy, user_id) ke tabel `predictions`
     * yang sudah ada, TANPA menyentuh data lama.
     *
     * Kolom lama (nama, kehadiran, prestasi, semester) dilonggarkan jadi
     * nullable karena alur baru tidak lagi mengisi field tersebut, tapi
     * data yang sudah ada di kolom itu tetap dibiarkan seperti semula.
     */
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->string('prodi')->nullable()->after('user_id');
            $table->unsignedInteger('sks')->nullable()->after('ipk');
            $table->unsignedInteger('tanggungan')->nullable()->after('penghasilan');
            $table->boolean('prediction')->nullable()->after('organisasi');
            $table->decimal('accuracy', 5, 2)->nullable()->after('confidence');
        });

        // Catatan: perintah MODIFY di bawah ini sintaks MySQL/MariaDB.
        // Kalau kamu pakai database lain (PostgreSQL/SQLite), kasih tahu
        // saya supaya saya sesuaikan sintaksnya.
        DB::statement('ALTER TABLE predictions MODIFY nama VARCHAR(255) NULL');
        DB::statement('ALTER TABLE predictions MODIFY kehadiran INT NULL');
        DB::statement('ALTER TABLE predictions MODIFY prestasi VARCHAR(255) NULL');
        DB::statement('ALTER TABLE predictions MODIFY semester INT NULL');
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id');
});

        DB::statement('ALTER TABLE predictions MODIFY nama VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY kehadiran INT NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY prestasi VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY semester INT NOT NULL');
    }
};
