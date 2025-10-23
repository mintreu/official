<?php

namespace App\Services\PluginService;

use App\Models\Plugin;
use Illuminate\Support\Str;

class PluginHandler
{


    public static function providerClass(Plugin $plugin): string
    {
        $vendor = Str::studly($plugin->vendor);
        $slug   = Str::studly(class_basename($plugin->slug));

        return "Plugins\\{$vendor}\\{$slug}\\PluginServiceProvider";
    }

    public static function providerPath(Plugin $plugin): string
    {
        return app_path("Plugins/{$plugin->vendor}/{$plugin->slug}/PluginServiceProvider.php");
    }

    public static function pluginClass(Plugin $plugin): string
    {
        $vendor = Str::studly($plugin->vendor);
        $slug   = Str::studly(class_basename($plugin->slug));
        $realSlug = Str::replace($plugin->vendor.'/','',$plugin->slug);

        $dir = "Plugins\\{$vendor}\\{$realSlug}";
        $serviceProvider = app_path($dir."/src/{$slug}ServiceProvider");
       // dd($dir,$serviceProvider,scandir(app_path("Plugins/{$vendor}/")),);
        require_once $serviceProvider.'.php';
       // require_once app_path($dir.'/'.$slug).'.php';

    }

    public static function pluginPath(Plugin $plugin): string
    {
        return app_path("Plugins/{$plugin->vendor}/{$plugin->slug}");
    }

}
