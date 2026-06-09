<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jurusanList = [
            'RPL', // Rekayasa Perangkat Lunak
            'TKJ', // Teknik Komputer dan Jaringan
            'AKL', // Akuntansi dan Keuangan Lembaga
            'BDP', // Bisnis Daring dan Pemasaran
            'OTKP', // Otomatisasi dan Tata Kelola Perkantoran
        ];

        $grade = $this->faker->randomElement([10, 11, 12]);
        $jurusan = $this->faker->randomElement($jurusanList);

        $name = "Kelas {$grade} {$jurusan} " . strtoupper($this->faker->randomLetter());

        return [
            'name' => $name,
            'grade' => $grade,
            'jurusan' => $jurusan,
            'capacity' => $this->faker->numberBetween(41, 45),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
        ];
    }
}
