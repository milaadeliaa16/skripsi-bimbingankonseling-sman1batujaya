<?php

namespace App\Filament\Resources\Absences\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AbsenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->where('type', User::ROLE_SISWA),
                    )
                    ->required()
                    ->searchable(['name', 'nisn'])
                    ->optionsLimit(50),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'name')
                    ->searchable()
                    ->preload(),

                DateTimePicker::make('date')
                    ->label('Tanggal')
                    ->required(),

                Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'alpa' => 'Alpa',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'terlambat' => 'Terlambat',
                    ])
                    ->required(),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->nullable(),
            ]);
    }
}
