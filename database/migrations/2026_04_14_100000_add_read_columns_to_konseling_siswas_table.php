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
        Schema::table('konseling_siswas', function (Blueprint $table): void {
            $table->boolean('is_read_by_counselor')
                ->default(false)
                ->after('scheduled_at');
            $table->dateTime('read_at_by_counselor')
                ->nullable()
                ->after('is_read_by_counselor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konseling_siswas', function (Blueprint $table): void {
            $table->dropColumn(['is_read_by_counselor', 'read_at_by_counselor']);
        });
    }
};
