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
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('question_group_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Make common fields nullable because they will be inherited from the group
            $table->foreignId('category_id')->nullable()->change();
            $table->foreignId('test_type_id')->nullable()->change();
            $table->foreignId('level_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};
