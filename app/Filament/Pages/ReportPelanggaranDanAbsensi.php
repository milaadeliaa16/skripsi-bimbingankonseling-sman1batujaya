<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ReportPelanggaranDanAbsensi extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Guru BK';

    protected static ?string $navigationLabel = 'Report Pelanggaran Siswa';

    protected static ?string $title = 'Report Pelanggaran & Absensi Siswa';

    protected static ?int $navigationSort = 14;

    protected string $view = 'filament.pages.report-pelanggaran-dan-absensi';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return in_array($user->type, [User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH], true)
            || $user->hasAnyRole([User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
