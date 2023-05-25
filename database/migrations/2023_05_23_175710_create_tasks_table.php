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
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('reminder_date')->nullable();
            $table->string('priority')->default('low');
            $table->string('status')->default('incomplete');
            $table->string('category')->default('uncategorized');
            $table->string('color')->default('gray');
            $table->string('icon')->default('question');
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
