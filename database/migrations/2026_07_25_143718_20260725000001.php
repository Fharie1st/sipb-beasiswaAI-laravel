<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('predictions', 'user_id')) {
            Schema::table('predictions', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
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
};
