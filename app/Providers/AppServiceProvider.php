<?php

namespace App\Providers;

use App\Models\Plugin;
use App\Services\PluginService\PluginHandler;
use App\Services\PluginService\PluginManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $manager = PluginManager::make();
//        $json = $manager->getRegistryData();
//
//        if (!$json) {
//            return;
//        }
//
//        $plugins = json_decode($json, true);
//
//        foreach ($plugins as $plugin) {
//            // Use absolute paths from registry
//            $providerFile = $plugin['files']['provider'] ?? null;
//            $classFile = $plugin['files']['class'] ?? null;
//
//            if ($classFile && file_exists($classFile)) {
//                require_once $classFile;
//            }
//
//            if ($providerFile && file_exists($providerFile)) {
//                require_once $providerFile;
//            }
//        }
    }

    public function boot(): void
    {
//        $manager = PluginManager::make()->resolve();
//
//        foreach ($manager->getAllActivePlugins() as $plugin) {
//            $pluginProvider = $plugin['provider'] ?? null;
//
//            if ($pluginProvider && class_exists($pluginProvider)) {
//                $this->app->register($pluginProvider);
//                $this->app->boot($pluginProvider);
//            }
//        }
    }
}
