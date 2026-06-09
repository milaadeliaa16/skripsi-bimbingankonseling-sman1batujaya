<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\AbsenceIssueSummaryTable;
use App\Filament\Widgets\AbsenceTopAlpaClassesChart;
use App\Filament\Widgets\AbsenceTopAlpaStudentsChart;
use App\Filament\Widgets\AbsenceTopLateClassesChart;
use App\Filament\Widgets\AbsenceTopLateStudentsChart;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\ViolationByClassChart;
use App\Filament\Widgets\ViolationByStudentChart;
use App\Filament\Widgets\ViolationSummaryTable;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Purple,
                // 'primary' => '#2597C',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                AbsenceTopAlpaClassesChart::class,
                AbsenceTopAlpaStudentsChart::class,
                AbsenceTopLateClassesChart::class,
                AbsenceTopLateStudentsChart::class,
                ViolationByClassChart::class,
                ViolationByStudentChart::class,
                AbsenceIssueSummaryTable::class,
                ViolationSummaryTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName(env('APP_NAME'))
            ->brandLogo(asset('assets/images/logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('favicon.ico'))
            ->unsavedChangesAlerts()
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationLabel('Role & Permission')
                    ->navigationIcon('heroicon-o-shield-check')
                    ->activeNavigationIcon('heroicon-s-shield-check')
                    ->navigationGroup('Users Management')
                    ->navigationSort(81)
                    ->navigationBadgeColor('success')
                    ->registerNavigation(true)
                    ->globallySearchable(false)
            ])
            ->sidebarCollapsibleOnDesktop(true);
    }
}
