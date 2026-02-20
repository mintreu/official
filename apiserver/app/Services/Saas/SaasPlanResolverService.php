<?php

namespace App\Services\Saas;

use Illuminate\Support\Facades\File;

class SaasPlanResolverService
{
    /**
     * @return array<string, mixed>
     */
    public function resolve(string $project, string $productSlug): array
    {
        $plansRoot = base_path('../plans');
        $core = $this->readJson($plansRoot.'/core/defaults.json');
        $projectData = $this->readJson($plansRoot.'/projects/'.$project.'.json');
        $productData = $this->readJson($plansRoot.'/products/'.$productSlug.'.json');

        $merged = array_replace_recursive($core, $projectData, $productData);

        return [
            'project' => $project,
            'product_slug' => $productSlug,
            'source_files' => [
                'core' => $plansRoot.'/core/defaults.json',
                'project' => $plansRoot.'/projects/'.$project.'.json',
                'product' => $plansRoot.'/products/'.$productSlug.'.json',
            ],
            'data' => $merged,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function readJson(string $path): array
    {
        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}
