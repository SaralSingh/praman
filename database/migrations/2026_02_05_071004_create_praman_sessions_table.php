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
        Schema::create('praman_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('list_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('session_date');

            // Ab ye zaroori ho gaya
            $table->string('title');

            $table->enum('status', ['active', 'closed'])
                ->default('active');

            $table->timestamps();

            // Critical change
            $table->unique(['list_id', 'session_date', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praman_sessions');
    }
};
