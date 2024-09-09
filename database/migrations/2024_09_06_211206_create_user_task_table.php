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
        Schema::create('user_task', function (Blueprint $table) {
            // $table->id();
            $table->bigIncrements('task_id');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('description')->nullable();
            $table->date('due_date');
            $table->enum('priority', ['low', 'medium','high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress','completed' ,'cancelled'])->default('pending');
            $table->foreignId('to_assigned')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task');
    }
};
