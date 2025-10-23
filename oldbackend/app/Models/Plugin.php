<?php

// app/Models/Plugin.php

namespace App\Models;

use App\Models\Enums\PluginStatusCast;
use App\Models\Enums\PluginTypeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','version','src_path','type','status','vendor',
    ];
    protected $casts = [
        'type'   => PluginTypeCast::class,
        'status' => PluginStatusCast::class,
    ];






    public static function createFromSource(array $entry): ?self
    {
        try {
            if (!isset($entry['title'])) throw new \Exception('Missing title');

            $slug    = Str::slug($entry['title']);
            $version = 'unknown';
            $srcPath = '';
            $vendor  = 'native'; // default

            if (isset($entry['git'])) {
                [$version, $srcPath] = self::resolveGitHubSource($entry['git'], $entry['branch'] ?? null);

                // Extract vendor from GitHub URL
                $urlParts = explode('/', trim(parse_url($entry['git'], PHP_URL_PATH), '/'));
                $vendor = $urlParts[0] ?? 'github';
            }
            elseif (isset($entry['src']) && str_ends_with($entry['src'], '.zip')) {
                if (!File::exists($entry['src'])) throw new \Exception("ZIP not found");
                $version = 'local';
                $srcPath = $entry['src'];

                // Allow optional 'vendor' field for local ZIPs
                $vendor = $entry['vendor'] ?? 'native';
            } else {
                throw new \Exception('Invalid source');
            }

            return self::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'     => $entry['title'],
                    'slug'     => $slug,
                    'vendor'   => $vendor,
                    'version'  => $version,
                    'src_path' => $srcPath,
                    'status'   => PluginStatusCast::MARKETPLACE,
                    'type'     => PluginTypeCast::STANDALONE,
                ]
            );
        } catch (\Throwable $e) {
            Log::error("[Plugin::createFromSource] {$entry['title']} → {$e->getMessage()}");
            return null;
        }
    }








    public static function resolveGitHubSource(string $gitUrl, ?string $branchOrTag = null): array
    {
        try {
            $url = str_ends_with($gitUrl, '.git') ? substr($gitUrl, 0, -4) : $gitUrl;
            $parts = explode('/', trim(parse_url($url, PHP_URL_PATH), '/'));
            $owner = $parts[count($parts)-2];
            $repo  = $parts[count($parts)-1];

            $api = "https://api.github.com/repos/$owner/$repo";
            $info = Http::withoutVerifying()->get("$api");
            if ($info->failed()) throw new \Exception('Repo info failed');

            $branch = $branchOrTag ?: $info->json('default_branch', 'main');

            if ($branchOrTag) {
                $resp = Http::withoutVerifying()->get("$api/releases/tags/$branchOrTag");
                if ($resp->successful()) {
                    $r = $resp->json();
                    return [$r['tag_name'] ?? $branchOrTag, $r['zipball_url']];
                }
                $resp = Http::withoutVerifying()->get("$api/branches/$branchOrTag");
                if ($resp->successful()) {
                    return [$branchOrTag, "https://github.com/$owner/$repo/archive/refs/heads/$branchOrTag.zip"];
                }
            }

            $resp = Http::withoutVerifying()->get("$api/branches/$branch");
            if ($resp->successful()) {
                return [$branch, "https://github.com/$owner/$repo/archive/refs/heads/$branch.zip"];
            }

            return ['dev', $gitUrl];
        } catch (\Throwable $e) {
            Log::warning("[Plugin::github] $gitUrl → {$e->getMessage()}");
            return ['dev', $gitUrl];
        }
    }

    public function install(): bool
    {
        $tempDir = storage_path("app/tmp_plugin_{$this->slug}");
        if (!is_dir($tempDir)) mkdir($tempDir, 0755, true);

        try {
            $installedPath = null;

            // Detect vendor/repo from source path (for GitHub)
            $vendor = $this->vendor ?? 'unknown';
            $repo   = $this->slug;

            if (str_contains($this->src_path, 'github.com')) {
                $parts = explode('/', parse_url($this->src_path, PHP_URL_PATH));
                $vendor = $parts[1] ?? 'unknown';
                $repo   = basename(str_replace('.git', '', $parts[2] ?? $this->slug));
            }

            $baseDir = app_path("Plugins/{$vendor}/{$repo}");

            // ZIP install
            if (str_ends_with($this->src_path, '.zip')) {
                $zipPath = storage_path("app/{$this->slug}.zip");
                file_put_contents($zipPath, file_get_contents($this->src_path));

                $zip = new \ZipArchive();
                if ($zip->open($zipPath) === true) {
                    $zip->extractTo($tempDir);
                    $zip->close();
                } else {
                    throw new \RuntimeException("Failed to open ZIP");
                }
                unlink($zipPath);

                // Expect only one root directory inside ZIP
                $dirs = array_filter(glob("{$tempDir}/*"), 'is_dir');
                if (count($dirs) !== 1) {
                    throw new \RuntimeException("Unexpected ZIP structure: multiple root folders.");
                }

                $actualExtracted = $dirs[0];

                // Remove previous installation
                if (is_dir($baseDir)) {
                    File::deleteDirectory($baseDir);
                }

                // Ensure parent directory exists
                File::ensureDirectoryExists(dirname($baseDir));

                // Move to Plugins/vendor/repo
                File::moveDirectory($actualExtracted, $baseDir);
            }

            // Git install
            elseif (str_ends_with($this->src_path, '.git')) {
                if (is_dir($baseDir)) {
                    File::deleteDirectory($baseDir);
                }

                File::ensureDirectoryExists(dirname($baseDir));
                shell_exec("git clone {$this->src_path} {$baseDir}");
            }

            if (!is_dir($baseDir)) {
                throw new \RuntimeException("Plugin installation failed: $baseDir not created.");
            }

            // Update model
            $this->update([
                'status' => PluginStatusCast::INSTALLED,
                'slug'   => "{$vendor}/{$repo}", // update slug to match installed path
                'src_path' => $this->src_path,
            ]);

            File::deleteDirectory($tempDir);
            return true;
        } catch (\Throwable $e) {
            Log::error("[Plugin::install] {$this->slug} → {$e->getMessage()}");
            File::deleteDirectory($tempDir);
            return false;
        }
    }




    public function activate(): void
    {
        $this->update(['status' => PluginStatusCast::ACTIVE]);
    }

    public function deactivate(): void
    {
        $this->update(['status' => PluginStatusCast::DOWNLOADED]);
    }

    public function uninstall(): void
    {
        $pluginDirectory = app_path("Plugins/{$this->slug}");

        if (is_dir($pluginDirectory)) {
            File::deleteDirectory($pluginDirectory);
        }

        $this->update(['status' => PluginStatusCast::MARKETPLACE]);
    }
}
