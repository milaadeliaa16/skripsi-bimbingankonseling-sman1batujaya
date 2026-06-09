<?php

namespace Database\Seeders;

use App\Models\Curhat;
use App\Models\CurhatMessage;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurhatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Curhat::query()
            ->with(['student', 'teacher'])
            ->get()
            ->each(function (Curhat $curhat): void {
                $messagesCount = fake()->numberBetween(2, 6);
                $currentTime = Carbon::instance(fake()->dateTimeBetween('-45 days', '-1 day'));

                for ($index = 1; $index <= $messagesCount; $index++) {
                    $senderType = $index % 2 === 0 ? User::ROLE_GURU_BK : User::ROLE_SISWA;
                    $isRead = $index === $messagesCount
                        ? fake()->boolean(60)
                        : true;

                    CurhatMessage::create([
                        'curhat_id' => $curhat->id,
                        'user_id' => $senderType === User::ROLE_GURU_BK
                            ? $curhat->teacher_id
                            : $curhat->student_id,
                        'sender_type' => $senderType,
                        'is_read' => $isRead,
                        'read_at' => $isRead ? $currentTime->copy()->addMinutes(fake()->numberBetween(1, 120)) : null,
                        'message' => [
                            'body' => fake()->paragraph(fake()->numberBetween(1, 3)),
                        ],
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ]);

                    $currentTime = $currentTime->copy()->addMinutes(fake()->numberBetween(5, 240));
                }

                $curhat->update([
                    'last_message_at' => $currentTime->copy()->subMinutes(fake()->numberBetween(5, 240)),
                    'status' => fake()->randomElement(['aktif', 'menunggu', 'selesai']),
                ]);
            });
    }
}
