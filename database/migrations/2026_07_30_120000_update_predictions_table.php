<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table) {

            if (!Schema::hasColumn('predictions', 'sks')) {
                $table->integer('sks')->nullable();
            }

            if (!Schema::hasColumn('predictions', 'tanggungan')) {
                $table->integer('tanggungan')->nullable();
            }

            if (!Schema::hasColumn('predictions', 'prediction')) {
                $table->integer('prediction')->nullable();
            }

            if (!Schema::hasColumn('predictions', 'accuracy')) {
                $table->double('accuracy')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
