<?php

namespace App\Providers;

use App\Models\Company;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->login()
            ->tenant(Company::class)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => Blade::render(<<<'HTML'
                    <script>
                    window.epCopyLink = function(url) {
                        function notify() {
                            var id = 'copy-' + Math.random().toString(36).substr(2, 9);
                            if (window.Livewire) {
                                window.Livewire.dispatch('notificationSent', {
                                    notification: { id: id, title: 'Link copied to clipboard!', status: 'success', body: url }
                                });
                            }
                        }
                        function fallback(u, cb) {
                            var ta = document.createElement('textarea');
                            ta.value = u;
                            ta.style.cssText = 'position:fixed;top:-9999px;opacity:0';
                            document.body.appendChild(ta);
                            ta.focus(); ta.select();
                            try { document.execCommand('copy'); cb(); } catch(e) {}
                            document.body.removeChild(ta);
                        }
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(url).then(notify).catch(function() { fallback(url, notify); });
                        } else {
                            fallback(url, notify);
                        }
                    };
                    </script>
                    HTML)
            )
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ]);
    }
}
