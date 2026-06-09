<?php

namespace Database\Factories;

use App\Models\KonselingGuruBk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KonselingGuruBk>
 */
class KonselingGuruBkFactory extends Factory
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

        $counselorId = User::query()
            ->where('type', User::ROLE_GURU_BK)
            ->inRandomOrder()
            ->value('id');

        $isRead = $this->faker->boolean(65);
        $problem = $this->faker->sentence(12);
        $summary = $this->faker->paragraph(2);
        $solution = $this->faker->paragraph(2);
        $notes = $this->faker->sentence(14);

        return [
            'student_id' => $studentId,
            'counselor_id' => $counselorId,
            'problem' => $this->makeTipTapDocument($problem),
            'summary' => $this->makeTipTapDocument($summary),
            'solution' => $this->makeTipTapDocument($solution),
            'notes' => $this->makeTipTapDocument($notes),
            'type_of_violation' => $this->faker->randomElement([
                'Terlambat',
                'Tidak mengerjakan tugas',
                'Seragam tidak rapi',
                'Mengganggu kelas',
                'Perilaku tidak sopan',
                'Bolos',
            ]),
            'point_of_violation' => $this->faker->numberBetween(1, 15),
            'history_of_violation' => (string) $this->faker->numberBetween(1, 10),
            'scheduled_at' => $this->faker->dateTimeBetween('-14 days', '+14 days'),
            'is_read_by_student' => $isRead,
            'read_at_by_student' => $isRead ? $this->faker->dateTimeBetween('-14 days', 'now') : null,
        ];
    }

    protected function makeTipTapDocument(string $text): array
    {
        return [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => trim($text),
                        ],
                    ],
                ],
            ],
        ];
    }
}
