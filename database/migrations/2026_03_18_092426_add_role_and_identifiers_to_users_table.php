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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['guru_bk', 'kepala_sekolah', 'siswa', 'web'])
                ->default('siswa')
                ->after('id');
            $table->string('nip')->nullable()->unique()->after('name');
            $table->string('nisn')->nullable()->unique()->after('nip');
            $table->string('alamat')->nullable()->after('nisn');
            $table->string('no_hp_orang_tua')->nullable()->after('alamat');
            $table->unsignedBigInteger('kelas_id')->nullable()->after('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kelas_id');
            $table->dropColumn(['type', 'nip', 'nisn']);
        });
    }
};
