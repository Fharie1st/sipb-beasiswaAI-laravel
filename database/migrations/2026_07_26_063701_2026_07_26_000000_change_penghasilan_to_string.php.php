<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kolom `penghasilan` semula dirancang menyimpan angka rupiah,
     * tapi model ML (model.pkl) ternyata memprediksi berdasarkan
     * kategori 'Rendah' / 'Sedang' / 'Tinggi', bukan angka mentah.
     * Migration ini mengubah tipe kolomnya jadi VARCHAR supaya bisa
     * menyimpan kategori tersebut.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE predictions MODIFY penghasilan VARCHAR(20) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE predictions MODIFY penghasilan DECIMAL(15,2) NULL');
    }
};