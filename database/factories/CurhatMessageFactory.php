<?php

namespace Database\Factories;

use App\Models\Curhat;
use App\Models\CurhatMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<CurhatMessage>
 */
class CurhatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $curhat = Curhat::query()->inRandomOrder()->first();

        $senderType = $this->faker->randomElement([User::ROLE_SISWA, User::ROLE_GURU_BK]);
        $userId = null;

        if ($curhat) {
            $userId = $senderType === User::ROLE_GURU_BK
                ? $curhat->teacher_id
                : $curhat->student_id;
        }

        $createdAt = Carbon::instance($this->faker->dateTimeBetween('-45 days', 'now'));
        $isRead = $this->faker->boolean(75);

        return [
            'curhat_id' => $curhat?->id,
            'user_id' => $userId,
            'sender_type' => $senderType,
            'is_read' => $isRead,
            'read_at' => $isRead ? $createdAt->copy()->addMinutes($this->faker->numberBetween(1, 180)) : null,
            'message' => [
                'body' => $this->faker->paragraph(),
            ],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
