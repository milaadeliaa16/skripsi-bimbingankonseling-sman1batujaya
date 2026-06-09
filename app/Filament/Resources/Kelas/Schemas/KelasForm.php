<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(debounce: 300)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug((string) $state)))
                    ->maxLength(255),
                Select::make('grade')
                    ->options([
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ])
                    ->required(),
                TextInput::make('jurusan')
                    ->label('Jurusan')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('capacity')
                    ->numeric()
                    ->default(40)
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->readOnly(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->nullable(),
            ]);
    }
}
