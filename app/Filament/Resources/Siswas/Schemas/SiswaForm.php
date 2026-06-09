<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\Kelas;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('type')
                    ->label('Type')
                    ->required()
                    ->default(User::ROLE_SISWA)
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('nisn')
                    ->label('NISN')
                    ->maxLength(50)
                    ->nullable()
                    ->numeric()
                    ->required(),
                // ->visible(fn($get) => $get('type') === User::ROLE_SISWA),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
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

                TextInput::make('no_hp_orang_tua')
                    ->label('No. HP Orang Tua')
                    ->maxLength(20)
                    ->nullable()
                    ->numeric()
                    ->maxLength(13),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(255)
                    ->nullable()
                    ->rows(3),
            ]);
    }
}
