<?php

namespace Database\Seeders;

use App\Models\KonselingSiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KonselingSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KonselingSiswa::factory()->count(10)->create();
    }
}
