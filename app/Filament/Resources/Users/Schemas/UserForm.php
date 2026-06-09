<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Kelas;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nip/NISN')
                    ->label('NIP/NISN')
                    ->maxLength(50)
                    ->nullable()
                    ->visible(fn($get) => $get('role') !== User::ROLE_GURU_BK),

                Select::make('jabatan')
                    ->options([
                        User::ROLE_GURU_BK => 'Guru BK',
                        User::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah',
                        User::ROLE_SISWA => 'Siswa',
                    ])
                    ->required(),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                TextInput::make('nisn')
                    ->label('NISN')
                    ->maxLength(50)
                    ->nullable()
                    ->visible(fn($get) => $get('role') === User::ROLE_SISWA),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),

                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
