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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('praman_session_id')
                ->constrained('praman_sessions')
                ->cascadeOnDelete();

            $table->foreignId('person_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_present')->default(0);

            $table->timestamps();

            $table->unique(['praman_session_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
