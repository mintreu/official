<?php

use App\Enums\SourceProvider;
use App\Services\GitReleaseService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->service = new GitReleaseService;
});

describe('parseRepoUrl', function () {
    it('parses GitHub HTTPS URL', function () {
        $result = $this->service->parseRepoUrl('https://github.com/mintreu/test-repo');

        expect($result)->toBe([
            'provider' => SourceProvider::GitHub,
            'owner' => 'mintreu',
            'repo' => 'test-repo',
        ]);
    });

    it('parses GitHub URL with .git suffix', function () {
        $result = $this->service->parseRepoUrl('https://github.com/mintreu/test-repo.git');

        expect($result)->toBe([
            'provider' => SourceProvider::GitHub,
            'owner' => 'mintreu',
            'repo' => 'test-repo',
        ]);
    });

    it('parses GitHub URL with trailing slash', function () {
        $result = $this->service->parseRepoUrl('https://github.com/laravel/laravel/');

        expect($result)->toBe([
            'provider' => SourceProvider::GitHub,
            'owner' => 'laravel',
            'repo' => 'laravel',
        ]);
    });

    it('parses GitLab URL', function () {
        $result = $this->service->parseRepoUrl('https://gitlab.com/owner/project');

        expect($result)->toBe([
            'provider' => SourceProvider::GitLab,
            'owner' => 'owner',
            'repo' => 'project',
        ]);
    });

    it('parses Bitbucket URL', function () {
        $result = $this->service->parseRepoUrl('https://bitbucket.org/owner/repo');

        expect($result)->toBe([
            'provider' => SourceProvider::Bitbucket,
            'owner' => 'owner',
            'repo' => 'repo',
        ]);
    });

    it('returns null for invalid URL', function () {
        $result = $this->service->parseRepoUrl('https://example.com/not-a-repo');

        expect($result)->toBeNull();
    });

    it('parses release URL and extracts repo info', function () {
        $result = $this->service->parseRepoUrl('https://github.com/mintreu/test-repo/releases/tag/v1');

        expect($result)->toBe([
            'provider' => SourceProvider::GitHub,
            'owner' => 'mintreu',
            'repo' => 'test-repo',
        ]);
    });
});

describe('getGitHubRepoInfo', function () {
    it('fetches public repo info', function () {
        Http::fake([
            'api.github.com/repos/laravel/laravel' => Http::response([
                'name' => 'laravel',
                'full_name' => 'laravel/laravel',
                'description' => 'Laravel application skeleton',
                'html_url' => 'https://github.com/laravel/laravel',
                'stargazers_count' => 75000,
                'forks_count' => 24000,
                'open_issues_count' => 10,
                'default_branch' => 'master',
                'topics' => ['php', 'laravel', 'framework'],
                'language' => 'PHP',
                'license' => ['spdx_id' => 'MIT'],
                'private' => false,
            ], 200),
        ]);

        $result = $this->service->getGitHubRepoInfo('laravel', 'laravel');

        expect($result)
            ->toBeArray()
            ->and($result['name'])->toBe('laravel')
            ->and($result['full_name'])->toBe('laravel/laravel')
            ->and($result['is_private'])->toBeFalse()
            ->and($result['default_branch'])->toBe('master')
            ->and($result['stargazers_count'])->toBe(75000);
    });

    it('fetches private repo info with token', function () {
        Http::fake([
            'api.github.com/repos/mintreu/test-repo' => Http::response([
                'name' => 'test-repo',
                'full_name' => 'mintreu/test-repo',
                'description' => 'Private test repository',
                'html_url' => 'https://github.com/mintreu/test-repo',
                'stargazers_count' => 0,
                'forks_count' => 0,
                'open_issues_count' => 0,
                'default_branch' => 'main',
                'topics' => [],
                'language' => 'PHP',
                'license' => null,
                'private' => true,
            ], 200),
        ]);

        $result = $this->service->getGitHubRepoInfo('mintreu', 'test-repo', 'fake-token');

        expect($result)
            ->toBeArray()
            ->and($result['name'])->toBe('test-repo')
            ->and($result['is_private'])->toBeTrue();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer fake-token');
        });
    });

    it('returns null for non-existent repo', function () {
        Http::fake([
            'api.github.com/repos/*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $result = $this->service->getGitHubRepoInfo('nonexistent', 'repo');

        expect($result)->toBeNull();
    });

    it('returns null for private repo without token', function () {
        Http::fake([
            'api.github.com/repos/*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $result = $this->service->getGitHubRepoInfo('mintreu', 'test-repo');

        expect($result)->toBeNull();
    });
});

describe('getGitHubReleases', function () {
    it('fetches releases from public repo', function () {
        Http::fake([
            'api.github.com/repos/laravel/laravel/releases' => Http::response([
                [
                    'tag_name' => 'v11.0.0',
                    'name' => 'Laravel 11',
                    'body' => 'Release notes for Laravel 11',
                    'published_at' => '2024-03-12T00:00:00Z',
                    'draft' => false,
                    'prerelease' => false,
                    'html_url' => 'https://github.com/laravel/laravel/releases/tag/v11.0.0',
                    'assets' => [],
                ],
                [
                    'tag_name' => 'v10.0.0',
                    'name' => 'Laravel 10',
                    'body' => 'Release notes for Laravel 10',
                    'published_at' => '2023-02-14T00:00:00Z',
                    'draft' => false,
                    'prerelease' => false,
                    'html_url' => 'https://github.com/laravel/laravel/releases/tag/v10.0.0',
                    'assets' => [],
                ],
            ], 200),
        ]);

        $releases = $this->service->getGitHubReleases('laravel', 'laravel');

        expect($releases)
            ->toHaveCount(2)
            ->and($releases->first()['tag_name'])->toBe('v11.0.0')
            ->and($releases->first()['name'])->toBe('Laravel 11');
    });

    it('fetches releases with assets', function () {
        Http::fake([
            'api.github.com/repos/*/releases' => Http::response([
                [
                    'tag_name' => 'v1.0.0',
                    'name' => 'Release 1.0',
                    'body' => 'First release',
                    'published_at' => '2024-01-01T00:00:00Z',
                    'draft' => false,
                    'prerelease' => false,
                    'html_url' => 'https://github.com/owner/repo/releases/tag/v1.0.0',
                    'assets' => [
                        [
                            'name' => 'release.zip',
                            'browser_download_url' => 'https://github.com/owner/repo/releases/download/v1.0.0/release.zip',
                            'size' => 1024000,
                            'content_type' => 'application/zip',
                            'download_count' => 100,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $releases = $this->service->getGitHubReleases('owner', 'repo');

        expect($releases)->toHaveCount(1)
            ->and($releases->first()['assets'])->toHaveCount(1)
            ->and($releases->first()['assets'][0]['name'])->toBe('release.zip')
            ->and($releases->first()['assets'][0]['size'])->toBe(1024000);
    });

    it('returns empty collection when no releases', function () {
        Http::fake([
            'api.github.com/repos/*/releases' => Http::response([], 200),
        ]);

        $releases = $this->service->getGitHubReleases('owner', 'repo');

        expect($releases)->toBeEmpty();
    });

    it('returns empty collection on API error', function () {
        Http::fake([
            'api.github.com/repos/*/releases' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $releases = $this->service->getGitHubReleases('nonexistent', 'repo');

        expect($releases)->toBeEmpty();
    });
});

describe('getLatestGitHubRelease', function () {
    it('fetches latest release', function () {
        Http::fake([
            'api.github.com/repos/*/releases/latest' => Http::response([
                'tag_name' => 'v1.0.0',
                'name' => 'Version 1.0',
                'body' => 'Release notes',
                'published_at' => '2024-01-01T00:00:00Z',
                'tarball_url' => 'https://api.github.com/repos/owner/repo/tarball/v1.0.0',
                'zipball_url' => 'https://api.github.com/repos/owner/repo/zipball/v1.0.0',
                'html_url' => 'https://github.com/owner/repo/releases/tag/v1.0.0',
                'assets' => [],
            ], 200),
        ]);

        $release = $this->service->getLatestGitHubRelease('owner', 'repo');

        expect($release)
            ->toBeArray()
            ->and($release['tag_name'])->toBe('v1.0.0')
            ->and($release['zipball_url'])->toContain('zipball/v1.0.0');
    });

    it('returns null when no releases exist', function () {
        Http::fake([
            'api.github.com/repos/*/releases/latest' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $release = $this->service->getLatestGitHubRelease('owner', 'repo');

        expect($release)->toBeNull();
    });
});

describe('getGitHubDownloadUrl', function () {
    it('returns archive URL for branch (public repo)', function () {
        $url = $this->service->getGitHubDownloadUrl('owner', 'repo', 'main');

        expect($url)->toBe('https://github.com/owner/repo/archive/refs/heads/main.zip');
    });

    it('returns archive URL for tag (public repo)', function () {
        $url = $this->service->getGitHubDownloadUrl('owner', 'repo', 'v1.0.0');

        expect($url)->toBe('https://github.com/owner/repo/archive/refs/tags/v1.0.0.zip');
    });

    it('returns API zipball URL for private repo with token', function () {
        $url = $this->service->getGitHubDownloadUrl('mintreu', 'test-repo', 'v1', null, 'fake-token');

        expect($url)->toBe('https://api.github.com/repos/mintreu/test-repo/zipball/v1');
    });

    it('returns asset URL when asset name provided', function () {
        Http::fake([
            'api.github.com/repos/*/releases' => Http::response([
                [
                    'tag_name' => 'v1.0.0',
                    'name' => 'Release 1.0',
                    'body' => '',
                    'published_at' => '2024-01-01T00:00:00Z',
                    'draft' => false,
                    'prerelease' => false,
                    'html_url' => 'https://github.com/owner/repo/releases/tag/v1.0.0',
                    'assets' => [
                        [
                            'name' => 'app.zip',
                            'browser_download_url' => 'https://github.com/owner/repo/releases/download/v1.0.0/app.zip',
                            'size' => 1024,
                            'content_type' => 'application/zip',
                            'download_count' => 10,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $url = $this->service->getGitHubDownloadUrl('owner', 'repo', 'v1.0.0', 'app.zip');

        expect($url)->toBe('https://github.com/owner/repo/releases/download/v1.0.0/app.zip');
    });
});

describe('checkRepoAccess', function () {
    it('returns true for accessible public repo', function () {
        Http::fake([
            'api.github.com/repos/*' => Http::response(['name' => 'repo'], 200),
        ]);

        $result = $this->service->checkRepoAccess('github', 'owner', 'repo');

        expect($result)->toBeTrue();
    });

    it('returns true for private repo with valid token', function () {
        Http::fake([
            'api.github.com/repos/*' => Http::response(['name' => 'repo', 'private' => true], 200),
        ]);

        $result = $this->service->checkRepoAccess('github', 'mintreu', 'test-repo', 'valid-token');

        expect($result)->toBeTrue();
    });

    it('returns false for private repo without token', function () {
        Http::fake([
            'api.github.com/repos/*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $result = $this->service->checkRepoAccess('github', 'mintreu', 'test-repo');

        expect($result)->toBeFalse();
    });

    it('returns false for invalid provider', function () {
        $result = $this->service->checkRepoAccess('invalid', 'owner', 'repo');

        expect($result)->toBeFalse();
    });
});
