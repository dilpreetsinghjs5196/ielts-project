<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listening_questions', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image');
        });
        
        // Migrate data
        DB::statement('UPDATE listening_questions SET images = JSON_ARRAY(image) WHERE image IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listening_questions', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
