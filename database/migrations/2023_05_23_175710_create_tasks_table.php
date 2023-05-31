<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('steps')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('actual_start_date')->nullable();
            $table->integer('progress')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('reminder_date')->nullable();
            $table->integer('working_days')->nullable();
            $table->integer('planned_effort')->nullable();
            $table->integer('actual_effort')->nullable();
            $table->integer('effort_difference')->nullable();
            $table->integer('cost')->nullable();
            $table->string('priority')->default('none');
            $table->string('status')->default('incomplete');
            $table->string('category')->default('uncategorized');
            $table->string('color')->default('gray-400');
            $table->string('icon')->default('fluent:question-24-filled');
            $table->boolean('public')->default(false);
            $table->json('team')->nullable();
            $table->json('labels')->nullable();
            $table->json('checklist')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
