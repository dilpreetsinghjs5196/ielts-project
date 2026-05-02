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
        Schema::create('listening_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('test_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->string('audio_file')->nullable();
            $table->string('status')->default('inactive');
            $table->timestamps();
        });

        Schema::create('listening_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listening_test_id')->constrained()->onDelete('cascade');
            $table->integer('part_number');
            $table->string('title')->nullable();
            $table->text('instruction')->nullable();
            $table->longText('passage')->nullable(); // For transcript if needed
            $table->string('audio_file')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('listening_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listening_part_id')->constrained()->onDelete('cascade');
            $table->string('question_number');
            $table->string('question_type');
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->json('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->string('image')->nullable();
            $table->integer('marks')->default(1);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_questions');
        Schema::dropIfExists('listening_parts');
        Schema::dropIfExists('listening_tests');
    }
};
