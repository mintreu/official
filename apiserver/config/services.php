<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Git Provider Credentials
    |--------------------------------------------------------------------------
    |
    | Tokens for accessing private repositories on various git hosting
    | platforms. These are used for fetching releases and downloading
    | private repo assets.
    |
    */

    'github' => [
        'token' => env('GITHUB_TOKEN'),
    ],

    'gitlab' => [
        'token' => env('GITLAB_TOKEN'),
    ],

    'bitbucket' => [
        'username' => env('BITBUCKET_USERNAME'),
        'password' => env('BITBUCKET_APP_PASSWORD'),
    ],

    'mintreu_saas' => [
        'internal_key' => env('MINTREU_SAAS_INTERNAL_KEY'),
        'internal_secret' => env('MINTREU_SAAS_INTERNAL_SECRET'),
        'max_skew_seconds' => (int) env('MINTREU_SAAS_MAX_SKEW_SECONDS', 300),
        'license_key' => env('MINTREU_SAAS_LICENSE_KEY'),
        'license_secret' => env('MINTREU_SAAS_LICENSE_SECRET'),
        'projects' => [
            'shopcore-commerce-api' => [
                'local_base_url' => env('MINTREU_CHILD_SHOPCORE_LOCAL_BASE_URL', 'http://shopcore.test'),
                'production_base_url' => env('MINTREU_CHILD_SHOPCORE_PROD_BASE_URL', 'https://shopcore-api.mintreu.com'),
                'provision_path' => env('MINTREU_CHILD_SHOPCORE_PROVISION_PATH', '/api/internal/saas/vendors/provision'),
                'internal_key' => env('MINTREU_CHILD_SHOPCORE_INTERNAL_KEY'),
                'internal_secret' => env('MINTREU_CHILD_SHOPCORE_INTERNAL_SECRET'),
                'timeout_seconds' => (int) env('MINTREU_CHILD_SHOPCORE_TIMEOUT', 15),
            ],
            'helpdesk-support-api' => [
                'local_base_url' => env('MINTREU_CHILD_HELPDESK_LOCAL_BASE_URL', 'http://helpdesk.test'),
                'production_base_url' => env('MINTREU_CHILD_HELPDESK_PROD_BASE_URL', 'https://helpdesk-api.mintreu.com'),
                'provision_path' => env('MINTREU_CHILD_HELPDESK_PROVISION_PATH', '/api/internal/saas/vendors/provision'),
                'internal_key' => env('MINTREU_CHILD_HELPDESK_INTERNAL_KEY'),
                'internal_secret' => env('MINTREU_CHILD_HELPDESK_INTERNAL_SECRET'),
                'timeout_seconds' => (int) env('MINTREU_CHILD_HELPDESK_TIMEOUT', 15),
            ],
        ],
    ],

];
