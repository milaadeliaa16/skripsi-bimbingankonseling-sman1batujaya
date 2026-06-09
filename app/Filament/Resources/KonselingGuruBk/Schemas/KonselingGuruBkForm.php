<?php

namespace App\Filament\Resources\KonselingGuruBk\Schemas;

use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KonselingGuruBkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                RichEditor::make('problem')
                    ->label('Jenis masalah')
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
                    ->resizableImages(),

                RichEditor::make('summary')
                    ->label('Ringkasan')
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
                    ->resizableImages(),

                RichEditor::make('solution')
                    ->label('Solusi')
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
                    ->resizableImages(),

                RichEditor::make('notes')
                    ->label('Catatan')
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
                    ->visible(fn() => auth()->user()->type === User::ROLE_GURU_BK),

                TextInput::make('type_of_violation')
                    ->label('Jenis Pelanggaran')
                    ->placeholder('Contoh: Bullying, Keterlambatan, dll.')
                    ->nullable(),

                TextInput::make('point_of_violation')
                    ->label('Poin Pelanggaran')
                    ->numeric()
                    ->nullable()
                    ->placeholder('Nilai 1 - 10.'),

                DateTimePicker::make('scheduled_at')
                    ->label('Jadwal Konseling')
                    ->required()
                    ->nullable(),

                // sini, buat page sendiri terkait sejarah pelanggaran2 siswa
                // belum, delete, karena sudah membuat table untuk riwayat pelanggaran siswa
                TextInput::make('history_of_violation')
                    ->label('Riwayat Pelanggaran')
                    ->nullable(),

            ])
            ->columns(1);
    }
}
