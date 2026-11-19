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
        Schema::table('exam_timings', function (Blueprint $table) {
            $table->boolean('listening_speed_1_5x')->default(false)->after('exam_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_timings', function (Blueprint $table) {
            $table->dropColumn('listening_speed_1_5x');
        });
    }
};
