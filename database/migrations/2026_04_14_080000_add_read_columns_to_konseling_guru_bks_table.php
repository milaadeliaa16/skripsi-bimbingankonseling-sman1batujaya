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
        Schema::table('konseling_guru_bks', function (Blueprint $table): void {
            $table->boolean('is_read_by_student')
                ->default(false)
                ->after('scheduled_at');
            $table->dateTime('read_at_by_student')
                ->nullable()
                ->after('is_read_by_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konseling_guru_bks', function (Blueprint $table): void {
            $table->dropColumn(['is_read_by_student', 'read_at_by_student']);
        });
    }
};
