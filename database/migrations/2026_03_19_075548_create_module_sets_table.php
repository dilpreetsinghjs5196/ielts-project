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
        Schema::create('module_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Module 1"
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Module (Listening, etc)
            $table->foreignId('test_type_id')->constrained()->onDelete('cascade'); // Academic / General
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_sets');
    }
};
