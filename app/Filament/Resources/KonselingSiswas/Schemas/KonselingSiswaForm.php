<?php

namespace App\Filament\Resources\KonselingSiswas\Schemas;

use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class KonselingSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_name')
                    ->label('Siswa')
                    ->required()
                    ->formatStateUsing(fn($state) => $state ?? Auth::user()->name)
                    ->disabled()
                    ->dehydrated(false),

                Hidden::make('student_id')
                    ->default(fn() => Auth::id()),

                Select::make('counselor_id')
                    ->label('Pilih Guru BK')
                    ->relationship(
                        name: 'counselor',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->guruBk()
                    )
                    ->disabled(fn () => Auth::user()?->type !== User::ROLE_SISWA)
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('problem')
                    ->label('Masalah yang Dihadapi')
                    ->options([
                        'Bimbingan Pribadi' => 'Bimbingan Pribadi',
                        'Bimbingan Belajar' => 'Bimbingan Belajar',
                        'Bimbingan Sosial' => 'Bimbingan Sosial',
                        'Bimbingan Karir' => 'Bimbingan Karir',
                        'Bimbingan Konseling' => 'Bimbingan Konseling',
                    ])
                    ->required(),

                DateTimePicker::make('scheduled_at')
                    ->label('Jadwal Konseling')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->default('pending')
                    ->options([
                        'pending' => 'Pending',
                        'dijadwalkan' => 'Dijadwalkan',
                        'selesai' => 'Selesai',
                        'ditindaklanjuti' => 'Ditindaklanjuti',
                    ])
                    ->required(),

                RichEditor::make('content')
                    ->label('Konten Konseling')
                    ->required()
                    ->json()
                    ->extraInputAttributes(['style' => 'min-height: 10rem;'])
                    ->toolbarButtons([
                        ['h1', 'h2', 'h3', 'bold', 'italic', 'underline', 'strike', 'small', 'subscript', 'superscript', 'link', 'alignStart', 'alignCenter', 'alignJustify', 'alignEnd',],
                        ['clearFormatting', 'blockquote', 'codeBlock', 'details', 'bulletList', 'orderedList',],
                        ['attachFiles', 'table', 'grid', 'gridDelete', 'horizontalRule'],
                        ['lead', 'code', 'textColor', 'undo', 'redo'],
                    ])
                    ->textColors([
                        'brand' => TextColor::make('Brand', '#0ea5e9'),
                        'warning' => TextColor::make('Warning', '#f59e0b', darkColor: '#fbbf24'),
                        ...TextColor::getDefaults(),
                    ])
                    ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])
                    ->fileAttachmentsDirectory('posts/media/' . Carbon::now()->format('Y/m'))
                    ->fileAttachmentsMaxSize(2048)
                    ->resizableImages()
                    ->columnSpanFull(),
            ]);
    }
}
