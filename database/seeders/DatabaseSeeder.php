<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            KelasSeeder::class,
            UserSeeder::class,
            AbsenceSeeder::class,
            KonselingGuruBkSeeder::class,
            KonselingSiswaSeeder::class,
            CurhatSeeder::class,
            CurhatMessageSeeder::class,
        ]);
    }
}
