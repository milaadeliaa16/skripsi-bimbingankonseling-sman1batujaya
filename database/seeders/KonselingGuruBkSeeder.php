<?php

namespace Database\Seeders;

use App\Models\KonselingGuruBk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class KonselingGuruBkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentIds = User::query()
            ->where('type', User::ROLE_SISWA)
            ->pluck('id')
            ->values();

        $counselorIds = User::query()
            ->where('type', User::ROLE_GURU_BK)
            ->pluck('id')
            ->values();

        if ($studentIds->isEmpty() || $counselorIds->isEmpty()) {
            return;
        }

        // Agar hasil seeding class ini konsisten: selalu 100 data terbaru.
        KonselingGuruBk::query()->delete();

        $targetRecords = 100;
        $heavyStudentCount = min(5, $studentIds->count());
        $heavyStudentIds = $studentIds->shuffle()->take($heavyStudentCount)->values();

        $created = 0;

        // Pastikan ada siswa dengan pelanggaran > 5 (minimal 6 per siswa terpilih).
        foreach ($heavyStudentIds as $heavyStudentId) {
            for ($index = 1; $index <= 6; $index++) {
                if ($created >= $targetRecords) {
                    break 2;
                }

                $this->createViolationRecord((int) $heavyStudentId, (int) $counselorIds->random(), $index);
                $created++;
            }
        }

        // Sisa data dilengkapi acak sampai total 100.
        while ($created < $targetRecords) {
            $studentId = (int) $studentIds->random();
            $studentViolationCount = KonselingGuruBk::query()
                ->where('student_id', $studentId)
                ->count() + 1;

            $this->createViolationRecord($studentId, (int) $counselorIds->random(), $studentViolationCount);
            $created++;
        }
    }

    protected function createViolationRecord(int $studentId, int $counselorId, int $violationOrder): void
    {
        $violationCatalog = [
            ['type' => 'Terlambat', 'point' => 5],
            ['type' => 'Tidak memakai seragam rapi', 'point' => 3],
            ['type' => 'Bolos', 'point' => 10],
            ['type' => 'Tidak mengerjakan tugas', 'point' => 4],
            ['type' => 'Mengganggu kelas', 'point' => 6],
            ['type' => 'Perilaku tidak sopan', 'point' => 7],
            ['type' => 'Merokok di area sekolah', 'point' => 12],
        ];

        $faker = fake('id_ID');
        $picked = $violationCatalog[array_rand($violationCatalog)];
        $isReadByStudent = $faker->boolean(65);

        $createdAt = Carbon::instance($faker->dateTimeBetween('-5 months', 'now'));
        $scheduledAt = (clone $createdAt)->addDays($faker->numberBetween(1, 14));

        KonselingGuruBk::factory()->create([
            'student_id' => $studentId,
            'counselor_id' => $counselorId,
            'problem' => $this->makeTipTapDocument($faker->sentence(12)),
            'summary' => $this->makeTipTapDocument($faker->paragraph(2)),
            'solution' => $this->makeTipTapDocument($faker->paragraph(2)),
            'notes' => $this->makeTipTapDocument($faker->sentence(14)),
            'type_of_violation' => $picked['type'],
            'point_of_violation' => $picked['point'],
            'history_of_violation' => (string) $violationOrder,
            'scheduled_at' => $scheduledAt,
            'is_read_by_student' => $isReadByStudent,
            'read_at_by_student' => $isReadByStudent ? (clone $createdAt)->addHours($faker->numberBetween(1, 48)) : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
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
