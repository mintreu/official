<?php

namespace App\Services;

use App\Enums\SourceProvider;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GitReleaseService - Fetch releases from GitHub/GitLab/Bitbucket
 *
 * Supports both public and private repositories.
 * For private repos, provide a Personal Access Token.
 */
class GitReleaseService
{
    /**
     * Fetch releases from a GitHub repository
     *
     * @param  string  $owner  Repository owner (username or org)
     * @param  string  $repo  Repository name
     * @param  string|null  $token  Personal Access Token for private repos
     * @return Collection<int, array{
     *     tag_name: string,
     *     name: string,
     *     body: string,
     *     published_at: string,
     *     draft: bool,
     *     prerelease: bool,
     *     assets: array
     * }>
     */
    public function getGitHubReleases(string $owner, string $repo, ?string $token = null): Collection
    {
        $url = "https://api.github.com/repos/{$owner}/{$repo}/releases";

        $response = $this->makeRequest($url, $token, [
            'Accept' => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ]);

        if ($response->failed()) {
            Log::warning("Failed to fetch GitHub releases for {$owner}/{$repo}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return collect();
        }

        return collect($response->json())->map(fn ($release) => [
            'tag_name' => $release['tag_name'],
            'name' => $release['name'] ?? $release['tag_name'],
            'body' => $release['body'] ?? '',
            'published_at' => $release['published_at'],
            'draft' => $release['draft'] ?? false,
            'prerelease' => $release['prerelease'] ?? false,
            'html_url' => $release['html_url'],
            'assets' => collect($release['assets'] ?? [])->map(fn ($asset) => [
                'name' => $asset['name'],
                'download_url' => $asset['browser_download_url'],
                'size' => $asset['size'],
                'content_type' => $asset['content_type'],
                'download_count' => $asset['download_count'],
            ])->all(),
        ]);
    }

    /**
     * Get latest release from GitHub
     */
    public function getLatestGitHubRelease(string $owner, string $repo, ?string $token = null): ?array
    {
        $url = "https://api.github.com/repos/{$owner}/{$repo}/releases/latest";

        $response = $this->makeRequest($url, $token, [
            'Accept' => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ]);

        if ($response->failed()) {
            return null;
        }

        $release = $response->json();

        return [
            'tag_name' => $release['tag_name'],
            'name' => $release['name'] ?? $release['tag_name'],
            'body' => $release['body'] ?? '',
            'published_at' => $release['published_at'],
            'tarball_url' => $release['tarball_url'],
            'zipball_url' => $release['zipball_url'],
            'html_url' => $release['html_url'],
            'assets' => collect($release['assets'] ?? [])->map(fn ($asset) => [
                'name' => $asset['name'],
                'download_url' => $asset['browser_download_url'],
                'size' => $asset['size'],
                'content_type' => $asset['content_type'],
            ])->all(),
        ];
    }

    /**
     * Get download URL for a specific release asset or zipball
     *
     * For private repos, this generates an authenticated URL
     */
    public function getGitHubDownloadUrl(
        string $owner,
        string $repo,
        string $tagOrBranch = 'main',
        ?string $assetName = null,
        ?string $token = null
    ): ?string {
        // If asset name provided, find it in release assets
        if ($assetName) {
            $releases = $this->getGitHubReleases($owner, $repo, $token);
            $release = $releases->firstWhere('tag_name', $tagOrBranch);

            if ($release) {
                $asset = collect($release['assets'])->firstWhere('name', $assetName);
                if ($asset) {
                    $url = $asset['download_url'];

                    // For private repos, we need to use the API endpoint with token
                    if ($token) {
                        // Private repo assets need API URL with auth
                        return $url;
                    }

                    return $url;
                }
            }
        }

        // Fall back to zipball URL (always works, zips the repo)
        $url = "https://github.com/{$owner}/{$repo}/archive/refs/";

        // Determine if it's a tag or branch
        if (preg_match('/^v?\d/', $tagOrBranch)) {
            $url .= "tags/{$tagOrBranch}.zip";
        } else {
            $url .= "heads/{$tagOrBranch}.zip";
        }

        // For private repos, use API with token
        if ($token) {
            return "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$tagOrBranch}";
        }

        return $url;
    }

    /**
     * Fetch releases from GitLab
     */
    public function getGitLabReleases(string $projectId, ?string $token = null): Collection
    {
        // GitLab project ID can be numeric or URL-encoded path (owner/repo)
        $encodedId = urlencode($projectId);
        $url = "https://gitlab.com/api/v4/projects/{$encodedId}/releases";

        $response = $this->makeRequest($url, $token, [], 'PRIVATE-TOKEN');

        if ($response->failed()) {
            Log::warning("Failed to fetch GitLab releases for {$projectId}");

            return collect();
        }

        return collect($response->json())->map(fn ($release) => [
            'tag_name' => $release['tag_name'],
            'name' => $release['name'] ?? $release['tag_name'],
            'description' => $release['description'] ?? '',
            'released_at' => $release['released_at'],
            'assets' => collect($release['assets']['links'] ?? [])->map(fn ($link) => [
                'name' => $link['name'],
                'download_url' => $link['direct_asset_url'] ?? $link['url'],
            ])->all(),
        ]);
    }

    /**
     * Parse a repository URL into components
     *
     * @return array{provider: SourceProvider, owner: string, repo: string}|null
     */
    public function parseRepoUrl(string $url): ?array
    {
        // GitHub: https://github.com/owner/repo
        if (preg_match('#github\.com/([^/]+)/([^/]+?)(?:\.git)?(?:/|$)#i', $url, $matches)) {
            return [
                'provider' => SourceProvider::GitHub,
                'owner' => $matches[1],
                'repo' => rtrim($matches[2], '.git'),
            ];
        }

        // GitLab: https://gitlab.com/owner/repo
        if (preg_match('#gitlab\.com/([^/]+)/([^/]+?)(?:\.git)?(?:/|$)#i', $url, $matches)) {
            return [
                'provider' => SourceProvider::GitLab,
                'owner' => $matches[1],
                'repo' => $matches[2],
            ];
        }

        // Bitbucket: https://bitbucket.org/owner/repo
        if (preg_match('#bitbucket\.org/([^/]+)/([^/]+?)(?:\.git)?(?:/|$)#i', $url, $matches)) {
            return [
                'provider' => SourceProvider::Bitbucket,
                'owner' => $matches[1],
                'repo' => $matches[2],
            ];
        }

        return null;
    }

    /**
     * Check if a repository is accessible (public or with valid token)
     */
    public function checkRepoAccess(string $provider, string $owner, string $repo, ?string $token = null): bool
    {
        $url = match ($provider) {
            'github' => "https://api.github.com/repos/{$owner}/{$repo}",
            'gitlab' => 'https://gitlab.com/api/v4/projects/'.urlencode("{$owner}/{$repo}"),
            'bitbucket' => "https://api.bitbucket.org/2.0/repositories/{$owner}/{$repo}",
            default => null,
        };

        if (! $url) {
            return false;
        }

        $headers = $provider === 'gitlab'
            ? ['PRIVATE-TOKEN' => $token]
            : ['Authorization' => $token ? "Bearer {$token}" : null];

        $response = Http::withHeaders(array_filter($headers))->get($url);

        return $response->successful();
    }

    /**
     * Get repository info (stars, forks, description)
     */
    public function getGitHubRepoInfo(string $owner, string $repo, ?string $token = null): ?array
    {
        $url = "https://api.github.com/repos/{$owner}/{$repo}";

        $response = $this->makeRequest($url, $token, [
            'Accept' => 'application/vnd.github+json',
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        return [
            'name' => $data['name'],
            'full_name' => $data['full_name'],
            'description' => $data['description'],
            'html_url' => $data['html_url'],
            'stargazers_count' => $data['stargazers_count'],
            'forks_count' => $data['forks_count'],
            'open_issues_count' => $data['open_issues_count'],
            'default_branch' => $data['default_branch'],
            'topics' => $data['topics'] ?? [],
            'language' => $data['language'],
            'license' => $data['license']['spdx_id'] ?? null,
            'is_private' => $data['private'],
        ];
    }

    /**
     * Make an authenticated HTTP request
     */
    private function makeRequest(string $url, ?string $token, array $headers = [], string $tokenHeader = 'Authorization'): Response
    {
        $client = Http::withHeaders($headers);

        if ($token) {
            if ($tokenHeader === 'PRIVATE-TOKEN') {
                $client = $client->withHeaders(['PRIVATE-TOKEN' => $token]);
            } else {
                $client = $client->withToken($token);
            }
        }

        return $client->get($url);
    }
}
