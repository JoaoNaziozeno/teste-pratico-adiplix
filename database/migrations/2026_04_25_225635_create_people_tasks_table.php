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
        Schema::create('people_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('people_id')
            ->constrained('people')
            ->OnDelete('cascade');

            $table->foreignId('tasks_id')
            ->constrained('tasks')
            ->OnDelete('cascade');

            $table->timestamps();

            $table->unique(['people_id', 'tasks_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people_tasks');
    }
};
