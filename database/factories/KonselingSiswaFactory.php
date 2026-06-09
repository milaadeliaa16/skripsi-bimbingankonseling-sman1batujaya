<?php

namespace Database\Factories;

use App\Models\KonselingSiswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KonselingSiswa>
 */
class KonselingSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed> 
     */
    public function definition(): array
    {
        $isReadByCounselor = $this->faker->boolean(55);

        return [
            'student_id' => User::siswa()->inRandomOrder()->first()->id,
            'counselor_id' => User::guruBk()->inRandomOrder()->first()->id,
            'problem' => $this->faker->randomElement(['Bimbingan Pribadi', 'Bimbingan Belajar', 'Bimbingan Sosial', 'Bimbingan Karir', 'Bimbingan Konseling']),
            'status' => $this->faker->randomElement(['pending', 'dijadwalkan', 'selesai', 'ditindaklanjuti']),
            'content' => $this->makeTipTapDocument($this->faker->paragraph()),
            'scheduled_at' => Carbon::now()->addDays(rand(1, 30)),
            'is_read_by_counselor' => $isReadByCounselor,
            'read_at_by_counselor' => $isReadByCounselor ? Carbon::now()->subHours(rand(1, 96)) : null,
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
