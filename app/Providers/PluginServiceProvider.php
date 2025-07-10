<?php

namespace App\Providers;

use App\Models\Plugin;
use App\Services\PluginService\PluginHandler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $activePlugins = Plugin::where('status', 'active')->get();

        dd($activePlugins);

        foreach ($activePlugins as $plugin) {
            $providerPath = PluginHandler::providerPath($plugin);
            $providerClass = PluginHandler::providerClass($plugin);

            if (File::exists($providerPath) && class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
