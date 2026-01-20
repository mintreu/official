<?php

namespace Database\Seeders;

use App\Models\StorageProvider;
use Illuminate\Database\Seeder;

class StorageProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name' => 'LOCAL_STORAGE',
                'display_name' => 'Local Storage (Laravel)',
                'description' => 'Store files in Laravel storage folder. Best for small files and development.',
                'icon' => 'heroicon-o-folder',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['path'],
                    'properties' => [
                        'path' => [
                            'type' => 'string',
                            'description' => 'Relative path in storage folder',
                        ],
                        'filename' => [
                            'type' => 'string',
                            'description' => 'Optional: Override filename',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => null,
            ],
            [
                'name' => 'GITHUB',
                'display_name' => 'GitHub Releases',
                'description' => 'Download from GitHub repository releases. Supports public and private repos.',
                'icon' => 'heroicon-o-square-3-stack-3d',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['repo_url', 'release_tag'],
                    'properties' => [
                        'repo_url' => [
                            'type' => 'string',
                            'description' => 'GitHub repository URL (github.com/user/repo)',
                        ],
                        'release_tag' => [
                            'type' => 'string',
                            'description' => 'Release tag or "latest"',
                        ],
                        'branch' => [
                            'type' => 'string',
                            'description' => 'Optional: Git branch name',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => 60,
            ],
            [
                'name' => 'BITBUCKET',
                'display_name' => 'Bitbucket Downloads',
                'description' => 'Download from Bitbucket repository releases and branches.',
                'icon' => 'heroicon-o-square-3-stack-3d',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['workspace', 'repo_slug'],
                    'properties' => [
                        'workspace' => [
                            'type' => 'string',
                            'description' => 'Bitbucket workspace name',
                        ],
                        'repo_slug' => [
                            'type' => 'string',
                            'description' => 'Repository slug',
                        ],
                        'branch' => [
                            'type' => 'string',
                            'description' => 'Branch name',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => 60,
            ],
            [
                'name' => 'GOOGLE_DRIVE',
                'display_name' => 'Google Drive',
                'description' => 'Share files directly from Google Drive. Requires service account.',
                'icon' => 'heroicon-o-cloud',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['folder_id'],
                    'properties' => [
                        'folder_id' => [
                            'type' => 'string',
                            'description' => 'Google Drive folder ID',
                        ],
                        'service_account_json' => [
                            'type' => 'string',
                            'description' => 'Service account credentials JSON',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => 100,
            ],
            [
                'name' => 'AWS_S3',
                'display_name' => 'Amazon S3',
                'description' => 'Store and serve files from AWS S3. Best for large files and scalability.',
                'icon' => 'heroicon-o-cloud-arrow-up',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['bucket', 'region'],
                    'properties' => [
                        'bucket' => [
                            'type' => 'string',
                            'description' => 'S3 bucket name',
                        ],
                        'region' => [
                            'type' => 'string',
                            'description' => 'AWS region (us-east-1, eu-west-1, etc)',
                        ],
                        'key_prefix' => [
                            'type' => 'string',
                            'description' => 'Optional: Prefix for keys',
                        ],
                        'acl' => [
                            'type' => 'string',
                            'description' => 'ACL setting (private, public-read, etc)',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => 1000,
            ],
            [
                'name' => 'DROPBOX',
                'display_name' => 'Dropbox',
                'description' => 'Share files from Dropbox. Requires app token.',
                'icon' => 'heroicon-o-cloud',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['app_token', 'folder_path'],
                    'properties' => [
                        'app_token' => [
                            'type' => 'string',
                            'description' => 'Dropbox app token',
                        ],
                        'folder_path' => [
                            'type' => 'string',
                            'description' => 'Folder path in Dropbox',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => 300,
            ],
            [
                'name' => 'EXTERNAL_URL',
                'display_name' => 'External URL',
                'description' => 'Direct link to external file. No authentication needed.',
                'icon' => 'heroicon-o-link',
                'config_schema' => [
                    'type' => 'object',
                    'required' => ['url'],
                    'properties' => [
                        'url' => [
                            'type' => 'string',
                            'description' => 'Direct URL to download file',
                        ],
                    ],
                ],
                'is_active' => true,
                'rate_limit' => null,
            ],
        ];

        foreach ($providers as $provider) {
            StorageProvider::create($provider);
        }
    }
}
