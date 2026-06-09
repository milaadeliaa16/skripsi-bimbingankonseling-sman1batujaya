<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            User::ROLE_SISWA,
            User::ROLE_GURU_BK,
            User::ROLE_KEPALA_SEKOLAH,
        ]);

        // return [
        //     'name' => fake()->name(),
        //     'email' => fake()->unique()->safeEmail(),
        //     'email_verified_at' => now(),
        //     'password' => static::$password ??= Hash::make('password'),
        //     'remember_token' => Str::random(10),
        //     'type' => $type,
        //     'nip' => $type !== User::ROLE_SISWA ? fake()->unique()->numerify('###############') : null,
        //     'nisn' => $type === User::ROLE_SISWA ? fake()->unique()->numerify('##########') : null,
        // ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'kelas_id' => Kelas::inRandomOrder()->value('id'),
            'type' => $type,
            'nip' => in_array($type, [User::ROLE_KEPALA_SEKOLAH, User::ROLE_GURU_BK])
                ? fake()->unique()->numerify('##################') // 18 digit
                : null,

            'nisn' => $type === User::ROLE_SISWA
                ? fake()->unique()->numerify('##########') // 10 digit
                : null,
            'alamat' => fake()->address(),
            'no_hp_orang_tua' => fake()->phoneNumber(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STATE (Best Practice), used for seeding data
    |--------------------------------------------------------------------------
    */

    public function kepalaSekolah()
    {
        return $this->state(fn() => [
            'type' => User::ROLE_KEPALA_SEKOLAH,
            'nip' => $this->faker->numerify('##################'),
            'nisn' => null,
        ]);
    }

    public function guruBk()
    {
        return $this->state(fn() => [
            'type' => User::ROLE_GURU_BK,
            'nip' => $this->faker->numerify('##################'),
            'nisn' => null,
        ]);
    }

    public function siswa()
    {
        return $this->state(fn() => [
            'type' => User::ROLE_SISWA,
            'nip' => null,
            'nisn' => $this->faker->numerify('##########'),
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
