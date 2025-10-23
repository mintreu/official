<?php

namespace App\Providers\Filament;

use App\Filament\Common\Resources\Studio\StudioResource;
use App\Models\Enums\PluginStatusCast;
use App\Services\PluginService\Plugin;
use App\Services\PluginService\PluginHandler;
use App\Services\PluginService\PluginManager;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('admin')
            ->login()
            ->colors([
                'primary' => Color::Purple,
            ])
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->plugins(array_merge([
                FilamentBackgroundsPlugin::make()
                    ->showAttribution(false),
            ],
                //$this->getActivePluginInstances()
            ))
            ->resources([
                StudioResource::class
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


    protected function getActivePluginInstances(): array
    {
        // Resolve plugins and cache the config for this request
        $manager = PluginManager::make();

        $json = $manager->getRegistryData();
        $plugins = json_decode($json, true) ?? [];
        $bag = [];
        if ($plugins)
        {

            foreach ($plugins as $plugin) {
                $class = $plugin['class'];
                if (class_exists($class)) {
                    $bag[] = $class::make();
                }
            }
        }


        return $bag;
    }



}
