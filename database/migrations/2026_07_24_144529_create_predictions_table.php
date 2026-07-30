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

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('prodi');
            $table->float('ipk');
            $table->integer('sks');

            $table->string('penghasilan');
            $table->integer('tanggungan');

            $table->string('organisasi');

            $table->integer('prediction')->nullable();
            $table->double('confidence')->nullable();
            $table->double('accuracy')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
