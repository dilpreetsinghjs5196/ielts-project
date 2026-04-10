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
        Schema::create('writing_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('writing_test_id');
            $table->json('answers')->nullable();
            $table->text('feedback')->nullable();
            $table->decimal('score', 3, 1)->nullable();
            $table->string('status')->default('pending'); // pending, graded
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Uncomment these if you have the students/writing_tests tables already
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // $table->foreign('writing_test_id')->references('id')->on('writing_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writing_attempts');
    }
};
