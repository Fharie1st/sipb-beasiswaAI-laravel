<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {

            $table->id();

            $table->string('nama');
            $table->float('ipk');
            $table->integer('kehadiran');
            $table->string('prestasi');
            $table->string('organisasi');
            $table->bigInteger('penghasilan');
            $table->integer('semester');

            $table->string('hasil')->nullable();
            $table->double('confidence')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};