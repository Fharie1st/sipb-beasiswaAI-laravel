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
    if (!Schema::hasColumn('predictions', 'user_id')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    if (!Schema::hasColumn('predictions', 'prodi')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->string('prodi')->nullable();
        });
    }

    if (!Schema::hasColumn('predictions', 'sks')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->unsignedInteger('sks')->nullable();
        });
    }

    if (!Schema::hasColumn('predictions', 'tanggungan')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->unsignedInteger('tanggungan')->nullable();
        });
    }

    if (!Schema::hasColumn('predictions', 'prediction')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->boolean('prediction')->nullable();
        });
    }

    if (!Schema::hasColumn('predictions', 'accuracy')) {
        Schema::table('predictions', function (Blueprint $table) {
            $table->decimal('accuracy', 5, 2)->nullable();
        });
    }
}



public function down(): void
{
    //
}

        DB::statement('ALTER TABLE predictions MODIFY nama VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY kehadiran INT NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY prestasi VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE predictions MODIFY semester INT NOT NULL');
    }
};
