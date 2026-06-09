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
        Schema::create('konseling_guru_bks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('users')->cascadeOnDelete();
            $table->longText('problem')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('solution')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('type_of_violation')->nullable();
            $table->integer('point_of_violation')->default(0);
            $table->longText('history_of_violation')->nullable();
            $table->dateTime('scheduled_at')->nullable();

            // sini, hapus kalau tidak lagi di pakai
            // $table->enum('type', ['pribadi', 'belajar', 'sosial', 'karir', 'konseling'])->default('konseling');
            // $table->enum('status', ['pending', 'dijadwalkan', 'selesai', 'ditindaklanjuti'])->default('pending');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konseling_reports');
    }
};
