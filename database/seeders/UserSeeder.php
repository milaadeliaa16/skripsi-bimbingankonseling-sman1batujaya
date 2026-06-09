<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'type' => 'web',
            'nip' => env('ADMIN_NIP'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ])->assignRole(User::ROLE_KEPALA_SEKOLAH);

        // KEPALA SEKOLAH
        User::factory()->create([
            'name' => 'Banara Uda Ardianto',
            'email' => 'kepala.sekolah@sekolah.test',
            'type' => User::ROLE_KEPALA_SEKOLAH,
            'nip' => '197501012019031002',
            'password' => Hash::make('password'),
        ])->assignRole(User::ROLE_KEPALA_SEKOLAH);

        // GURU BK
        User::factory()->create([
            'name' => 'Chelsea Usamah',
            'email' => 'guru.bk.1@sekolah.test',
            'type' => User::ROLE_GURU_BK,
            'nip' => '198001012019031001',
            'password' => Hash::make('password'),
        ])->assignRole(User::ROLE_GURU_BK);

        User::factory()->create([
            'name' => 'Okta Nugroho',
            'email' => 'guru.bk.2@sekolah.test',
            'type' => User::ROLE_GURU_BK,
            'nip' => '2374572234394443',
            'password' => Hash::make('password'),
        ])->assignRole(User::ROLE_GURU_BK);

        User::factory()->create([
            'name' => 'Dalima Winarsih',
            'email' => 'guru.bk.3@sekolah.test',
            'type' => User::ROLE_GURU_BK,
            'nip' => '523186930021111372',
            'password' => Hash::make('password'),
        ])->assignRole(User::ROLE_GURU_BK);

        // SISWA
        User::factory()->create([
            'name' => 'Naradi Hutapea',
            'email' => 'naradichariyah@example.net',
            'type' => User::ROLE_SISWA,
            'nisn' => '3421078151',
            'password' => Hash::make('password'),
            'kelas_id' => 1,
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'no_hp_orang_tua' => '081234567890',
        ])->assignRole(User::ROLE_SISWA);

        // 20 Siswa
        User::factory()
            ->count(20)
            ->siswa()
            ->create()
            ->each(fn(User $user) => $user->assignRole(User::ROLE_SISWA));
    }
}
