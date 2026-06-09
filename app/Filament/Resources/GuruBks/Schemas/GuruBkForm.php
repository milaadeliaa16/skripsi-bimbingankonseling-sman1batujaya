<?php

namespace App\Filament\Resources\GuruBks\Schemas;

use App\Models\Kelas;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class GuruBkForm
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
                    ->default(User::ROLE_GURU_BK)
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(50)
                    ->nullable()
                    ->numeric()
                    ->required(),

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
            ]);
    }
}
