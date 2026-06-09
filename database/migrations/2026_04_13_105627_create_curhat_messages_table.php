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
        Schema::create('curhat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curhat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // bisa student
            $table->string('sender_type'); // 'student' atau 'teacher'
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->json('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curhat_messages');
    }
};
