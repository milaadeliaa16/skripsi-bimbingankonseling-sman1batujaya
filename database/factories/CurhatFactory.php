<?php

namespace Database\Factories;

use App\Models\Curhat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Curhat>
 */
class CurhatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $studentId = User::query()
            ->where('type', User::ROLE_SISWA)
            ->inRandomOrder()
            ->value('id');

        $teacherId = User::query()
            ->where('type', User::ROLE_GURU_BK)
            ->inRandomOrder()
            ->value('id');

        $lastMessageAt = Carbon::instance($this->faker->dateTimeBetween('-60 days', 'now'));

        return [
            'student_id' => $studentId,
            'teacher_id' => $teacherId,
            'title' => $this->faker->randomElement([
                'Ingin cerita tentang pertemanan',
                'Butuh arahan soal belajar',
                'Cemas menjelang ujian',
                'Ingin konsultasi tentang keluarga',
                'Kesulitan adaptasi di kelas',
            ]),
            'is_anonymous' => $this->faker->boolean(35),
            'status' => $this->faker->randomElement(['aktif', 'menunggu', 'selesai']),
            'last_message_at' => $lastMessageAt,
        ];
    }
}
