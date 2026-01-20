<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Source providers for product downloads
 * Handles different storage/repository platforms
 */
enum SourceProvider: string implements HasColor, HasIcon, HasLabel
{
    case GitHub = 'github';
    case GitLab = 'gitlab';
    case Bitbucket = 'bitbucket';
    case GoogleDrive = 'google_drive';
    case Dropbox = 'dropbox';
    case S3 = 's3';
    case DirectUrl = 'direct_url';
    case LocalStorage = 'local';

    public function getLabel(): string
    {
        return match ($this) {
            self::GitHub => 'GitHub',
            self::GitLab => 'GitLab',
            self::Bitbucket => 'Bitbucket',
            self::GoogleDrive => 'Google Drive',
            self::Dropbox => 'Dropbox',
            self::S3 => 'Amazon S3',
            self::DirectUrl => 'Direct URL',
            self::LocalStorage => 'Local Storage',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::GitHub => 'gray',
            self::GitLab => 'warning',
            self::Bitbucket => 'info',
            self::GoogleDrive => 'success',
            self::Dropbox => 'primary',
            self::S3 => 'warning',
            self::DirectUrl => 'gray',
            self::LocalStorage => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::GitHub, self::GitLab, self::Bitbucket => 'heroicon-o-code-bracket',
            self::GoogleDrive, self::Dropbox, self::S3 => 'heroicon-o-cloud',
            self::DirectUrl => 'heroicon-o-link',
            self::LocalStorage => 'heroicon-o-server',
        };
    }

    /**
     * Whether this provider requires authentication token
     */
    public function requiresAuth(): bool
    {
        return in_array($this, [
            self::GitHub,
            self::GitLab,
            self::Bitbucket,
            self::GoogleDrive,
            self::Dropbox,
            self::S3,
        ]);
    }

    /**
     * Whether source URL should be kept private (masked in downloads)
     */
    public function isPrivate(): bool
    {
        return $this !== self::DirectUrl;
    }

    /**
     * URL pattern for release downloads (if applicable)
     *
     * @param  array{owner: string, repo: string, tag?: string, asset?: string}  $params
     */
    public function buildDownloadUrl(array $params): ?string
    {
        return match ($this) {
            self::GitHub => sprintf(
                'https://github.com/%s/%s/releases/download/%s/%s',
                $params['owner'],
                $params['repo'],
                $params['tag'] ?? 'latest',
                $params['asset'] ?? ''
            ),
            self::GitLab => sprintf(
                'https://gitlab.com/%s/%s/-/releases/%s/downloads/%s',
                $params['owner'],
                $params['repo'],
                $params['tag'] ?? 'latest',
                $params['asset'] ?? ''
            ),
            default => null,
        };
    }
}
