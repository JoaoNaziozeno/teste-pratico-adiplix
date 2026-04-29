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
        Schema::table('people_task', function (Blueprint $table) {
            $table->renameColumn('people_id', 'person_id');
            $table->renameColumn('tasks_id', 'task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people_task', function (Blueprint $table) {
            $table->renameColumn('person_id', 'people_id');
            $table->renameColumn('task_id', 'tasks_id');
        });
    }
};
