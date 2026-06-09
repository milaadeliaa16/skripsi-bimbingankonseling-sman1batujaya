<?php

namespace Database\Factories;

use App\Models\Absence;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Absence>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::siswa()->inRandomOrder()->first()->id,
            'kelas_id' => Kelas::inRandomOrder()->value('id'),
            'date' => Carbon::now()->addDays(rand(1, 30)),
            'status' => $this->faker->randomElement(['hadir', 'alpa', 'izin', 'sakit', 'terlambat']),
            'notes' => $this->faker->text,
        ];
    }
}
