<?php

namespace App\Services\PluginService;

use App\Models\Enums\PluginStatusCast;
use App\Models\Plugin as PluginModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PluginManager
{

    protected string $plugin_path;
    protected string $plugin_registry_file;
    protected $activePlugins;
    protected array $config = [];


    public function __construct()
    {
        $this->plugin_path = app_path('Plugins');

        if (!File::exists($this->plugin_path)) {
            File::makeDirectory($this->plugin_path, 0755, true);
        }

        $this->plugin_registry_file = $this->plugin_path . DIRECTORY_SEPARATOR . 'registry.json';
    }




    public static function make(): static
    {
        return new static();
    }


    public function resolve(): static
    {
        $this->activePlugins = PluginModel::where('status', PluginStatusCast::ACTIVE)->get();
        $registry = [];

        foreach ($this->activePlugins as $plugin) {
            $vendor = Str::studly($plugin->vendor);
            $slug = Str::studly(class_basename($plugin->slug));
            //$realSlug = Str::studly(Str::replace($plugin->vendor.'/','',$plugin->slug));
            $namespace = $vendor . '\\' . $slug;
            $pluginDir = $this->plugin_path . DIRECTORY_SEPARATOR . $plugin->slug . DIRECTORY_SEPARATOR . 'src';
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $pluginDir);

            $provider = "{$namespace}\\{$slug}ServiceProvider";
            $class = "{$namespace}\\{$slug}";


            $this->config[] = [
                'namespace' => $namespace,
                'provider' => $provider,
                'class' => $class,
                'files' => [
                    'provider' => "{$pluginDir}/{$slug}ServiceProvider.php",
                    'class' => "{$pluginDir}/{$slug}.php"
                ],
            ];

            $registry[] = [
                'namespace' => $namespace,
                'provider' => $provider,
                'class' => $class,
                'path' => $relativePath,
                'files' => [
                    'provider' => "{$pluginDir}/{$slug}ServiceProvider.php",
                    'class' => "{$pluginDir}/{$slug}.php"
                ],
            ];
        }


        // Save into json file

        try {
            File::put($this->plugin_registry_file, json_encode($registry, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $this;
    }


    public  function getAllActivePlugins():array
    {
       return $this->config;
    }


    public function getRegistryFile()
    {
        return $this->plugin_registry_file;
    }


    public function getRegistryData()
    {
        if (file_exists($this->plugin_registry_file))
        {
            return file_get_contents($this->plugin_registry_file);
        }else{
            return null;
        }

    }
}
