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
        Schema::table('question_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('question_groups', 'image')) {
                $table->string('image')->after('instruction')->nullable();
            }
        });

        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'image')) {
                $table->string('image')->after('content')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('question_groups', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
