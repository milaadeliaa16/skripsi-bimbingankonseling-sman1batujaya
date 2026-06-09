<?php

namespace Database\Seeders;

use App\Models\Curhat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurhatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentIds = User::query()
            ->where('type', User::ROLE_SISWA)
            ->pluck('id');

        $teacherIds = User::query()
            ->where('type', User::ROLE_GURU_BK)
            ->pluck('id');

        if ($studentIds->isEmpty() || $teacherIds->isEmpty()) {
            return;
        }

        Curhat::factory()
            ->count(20)
            ->state(fn () => [
                'student_id' => $studentIds->random(),
                'teacher_id' => $teacherIds->random(),
            ])
            ->create();
    }
}
