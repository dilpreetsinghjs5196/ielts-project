<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speaking_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaking_test_id')->constrained()->onDelete('cascade');
            $table->integer('part_number'); // 1, 2, or 3
            $table->string('title')->nullable();
            $table->text('instruction')->nullable();
            $table->longText('passage')->nullable(); // For Cue Card in Part 2 or Intro in Part 1/3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speaking_parts');
    }
};
