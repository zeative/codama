<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn(): string => new HtmlString('
            <link rel="icon" type="image/png" href="/seo/favicon-96x96.png" sizes="96x96" />
            <link rel="icon" type="image/svg+xml" href="/seo/favicon.svg" />
            <link rel="shortcut icon" href="/seo/favicon.ico" />
            <link rel="apple-touch-icon" sizes="180x180" href="/seo/apple-touch-icon.png" />
            <meta name="apple-mobile-web-app-title" content="Codama" />
            <link rel="manifest" href="/seo/site.webmanifest" />
        ')
        );

        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->brandName('Codama | Ekstrakulikuler Coding SMKN 2 Sukoharjo')
            ->brandLogo('/codama-light.svg')
            ->darkModeBrandLogo('/codama-dark.svg')
            ->login()
            ->registration()
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->globalSearch(false)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
