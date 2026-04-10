<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writing_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('writing_test_id')->constrained()->onDelete('cascade');
            $table->integer('task_number'); // 1 or 2
            $table->string('title')->nullable(); // Task 1, Task 2
            $table->text('instruction')->nullable(); // You should spend about 20 minutes...
            $table->longText('question_text')->nullable(); // The actual prompt
            $table->string('image')->nullable(); // Path to graph/chart if any
            $table->longText('sample_answer')->nullable();
            $table->decimal('marks', 3, 1)->default(9.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writing_tasks');
    }
};
