<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class MailingSystem extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'Guru BK';

    protected static ?string $navigationLabel = 'Mailing System';

    protected static ?string $title = 'Mailing System';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.mailing-system';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return in_array($user->type, [User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH], true)
            || $user->hasAnyRole([User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH]);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email Tujuan')
                    ->required()
                    ->placeholder('contoh: siswa1@mail.com, siswa2@mail.com'),

                TextInput::make('cc')
                    ->label('CC')
                    ->placeholder('opsional, pisahkan dengan koma'),

                TextInput::make('bcc')
                    ->label('BCC')
                    ->placeholder('opsional, pisahkan dengan koma'),

                Textarea::make('body')
                    ->label('Body')
                    ->required()
                    ->rows(8)
                    ->placeholder('Tulis isi email...'),

                FileUpload::make('attachment_pdf')
                    ->label('Upload PDF')
                    ->required()
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['file', 'mimes:pdf', 'mimetypes:application/pdf'])
                    ->disk('public')
                    ->directory(fn (): string => $this->getMailAttachmentDirectory())
                    ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file): string {
                        $directory = $this->getMailAttachmentDirectory();
                        $fileName = Str::ulid() . '.' . $file->getClientOriginalExtension();

                        return $file->storePubliclyAs($directory, $fileName, $component->getDiskName());
                    })
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable(),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $state = $this->form->getState();

        $to = $this->parseEmailList((string) ($state['email'] ?? ''), 'email');
        $cc = $this->parseEmailList((string) ($state['cc'] ?? ''), 'cc');
        $bcc = $this->parseEmailList((string) ($state['bcc'] ?? ''), 'bcc');
        $body = (string) ($state['body'] ?? '');
        $attachmentPath = $state['attachment_pdf'] ?? null;

        if (! $attachmentPath || ! is_string($attachmentPath)) {
            throw ValidationException::withMessages([
                'data.attachment_pdf' => 'File PDF wajib diupload.',
            ]);
        }

        Mail::send([], [], function ($message) use ($to, $cc, $bcc, $body, $attachmentPath): void {
            $message
                ->to($to)
                ->subject('Bimbingan Konseling - ' . config('app.name'))
                ->html(nl2br(e($body)));

            if ($cc !== []) {
                $message->cc($cc);
            }

            if ($bcc !== []) {
                $message->bcc($bcc);
            }

            $message->attach(Storage::disk('public')->path($attachmentPath), [
                'as' => basename($attachmentPath),
                'mime' => 'application/pdf',
            ]);
        });

        Notification::make()
            ->title('Email berhasil dikirim')
            ->success()
            ->send();

        $this->form->fill([
            'email' => null,
            'cc' => null,
            'bcc' => null,
            'body' => null,
            'attachment_pdf' => null,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function parseEmailList(string $value, string $field): array
    {
        $value = trim($value);

        if ($value === '') {
            return [];
        }

        $emails = collect(preg_split('/[\s,;]+/', $value) ?: [])
            ->map(fn(string $email): string => trim($email))
            ->filter()
            ->unique()
            ->values();

        $invalid = $emails
            ->filter(fn(string $email): bool => ! filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values();

        if ($invalid->isNotEmpty()) {
            throw ValidationException::withMessages([
                "data.{$field}" => 'Format email tidak valid: ' . $invalid->implode(', '),
            ]);
        }

        return $emails->all();
    }

    private function getMailAttachmentDirectory(): string
    {
        return 'mailing-attachments/' . now()->format('Y/m/d');
    }
}
