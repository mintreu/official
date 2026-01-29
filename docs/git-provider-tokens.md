# Git Provider Tokens Setup Guide

This guide explains how to configure Git provider tokens for downloading private repositories.

## Overview

Mintreu supports downloading products from private Git repositories (GitHub, GitLab, Bitbucket). To access private repos, you need to configure authentication tokens.

## Token Priority (Fallback Chain)

When downloading from a private repo, the system checks for tokens in this order:

1. **GitAccountToken** (DB) - Per-source or per-account tokens stored in database
2. **encrypted_token** (Legacy) - Per-source token stored on ProductSource
3. **Config token** (`.env`) - Global fallback token

## GitHub Token Setup

### Step 1: Create a Fine-grained Personal Access Token

1. Go to [GitHub Settings > Developer Settings > Personal Access Tokens > Fine-grained tokens](https://github.com/settings/personal-access-tokens/new)

2. Configure the token:
   - **Token name**: `mintreu-download-service` (or descriptive name)
   - **Expiration**: 90 days (recommended) or custom
   - **Resource owner**: Select your organization (e.g., `mintreu`)
   - **Repository access**: Select "Only select repositories"
   - Select the specific repos you want to access

3. Set permissions (minimal required):
   | Permission | Access Level | Purpose |
   |------------|--------------|---------|
   | **Contents** | Read-only | Download archives, zipballs, release assets |
   | **Metadata** | Read-only | Get repo info (auto-granted) |

4. Click "Generate token" and copy the token immediately (you won't see it again)

### Step 2: Add Token to System

#### Option A: Global Config Token (Simple)

Add to your `.env` file:

```env
GITHUB_TOKEN=github_pat_xxxxxxxxxxxxxxxxxxxx
```

This token will be used for all GitHub sources that don't have a specific token assigned.

#### Option B: Database Token (Multi-Account - Recommended)

Use the Filament admin panel or create via tinker:

```php
use App\Models\GitAccountToken;

GitAccountToken::createWithToken([
    'name' => 'Mintreu Organization',
    'provider' => 'github',
    'account_identifier' => 'mintreu',
    'scopes' => ['contents:read', 'metadata:read'],
    'notes' => 'For private product repos',
    'expires_at' => now()->addDays(90),
], 'github_pat_xxxxxxxxxxxxxxxxxxxx');
```

Then assign to ProductSource:

```php
$source = ProductSource::find(1);
$source->git_account_token_id = $token->id;
$source->save();
```

## GitLab Token Setup

### Create Personal Access Token

1. Go to [GitLab > User Settings > Access Tokens](https://gitlab.com/-/user_settings/personal_access_tokens)

2. Configure:
   - **Token name**: `mintreu-download`
   - **Expiration date**: Set appropriate date
   - **Scopes**: Select `read_repository`

3. Create token and copy

### Add to System

```env
GITLAB_TOKEN=glpat-xxxxxxxxxxxxxxxxxxxx
```

Or via database:

```php
GitAccountToken::createWithToken([
    'name' => 'GitLab Account',
    'provider' => 'gitlab',
    'account_identifier' => 'your-username',
], 'glpat-xxxxxxxxxxxxxxxxxxxx');
```

## Bitbucket Token Setup

### Create App Password

1. Go to [Bitbucket > Personal Settings > App passwords](https://bitbucket.org/account/settings/app-passwords/)

2. Create app password with `repository:read` scope

### Add to System

```env
BITBUCKET_USERNAME=your-username
BITBUCKET_APP_PASSWORD=xxxxxxxxxxxxxxxxxxxx
```

## Multi-Account Support

For organizations with multiple GitHub accounts/orgs:

```php
// Account 1: Main organization
GitAccountToken::createWithToken([
    'name' => 'Mintreu Org',
    'provider' => 'github',
    'account_identifier' => 'mintreu',
], 'token_for_mintreu_org');

// Account 2: Partner organization
GitAccountToken::createWithToken([
    'name' => 'Partner Org',
    'provider' => 'github',
    'account_identifier' => 'partner-org',
], 'token_for_partner_org');

// Assign to sources
$mintreProduct->sources()->first()->update(['git_account_token_id' => 1]);
$partnerProduct->sources()->first()->update(['git_account_token_id' => 2]);
```

## Token Verification

Verify a token is working:

```php
$token = GitAccountToken::find(1);

if ($token->verify()) {
    echo "Token is valid!";
} else {
    echo "Token is invalid or expired";
}
```

## Security Best Practices

1. **Minimal Permissions**: Only grant `Contents: Read-only` - nothing more
2. **Repository Scoping**: Select specific repos, not "All repositories"
3. **Expiration**: Set 90-day expiration and rotate tokens regularly
4. **Database Storage**: Tokens are encrypted using Laravel's encryption (AES-256-CBC)
5. **Never Commit**: Never commit tokens to git - use `.env` or database
6. **Audit**: Check `last_used_at` to monitor token usage

## Troubleshooting

### Token Not Working

```php
// Check token status
$token = GitAccountToken::find(1);
echo "Active: " . ($token->is_active ? 'Yes' : 'No');
echo "Expired: " . ($token->isExpired() ? 'Yes' : 'No');
echo "Usable: " . ($token->isUsable() ? 'Yes' : 'No');

// Verify with provider
$token->verify(); // Returns true/false
```

### Download Returns 404

1. Check repo exists and token has access
2. Verify token has `Contents: Read` permission
3. Check if repo is in token's "selected repositories"

### Download Returns 401

1. Token may be expired - check `expires_at`
2. Token may have been revoked on GitHub
3. Run `$token->verify()` to test

## API Reference

### GitAccountToken Model

```php
// Create with encrypted token
GitAccountToken::createWithToken([
    'name' => 'Token Name',
    'provider' => 'github', // github, gitlab, bitbucket
    'account_identifier' => 'org-name',
    'scopes' => ['contents:read'],
    'expires_at' => now()->addDays(90),
], 'raw_token_string');

// Get decrypted token
$token->getToken(); // Returns raw token

// Check if usable
$token->isUsable(); // active && not expired

// Verify with provider API
$token->verify(); // Makes API call to verify

// Mark as used (auto-called by ProductSource)
$token->markUsed();
```

### ProductSource Token Resolution

```php
$source = ProductSource::find(1);

// Get effective token (checks all fallbacks)
$token = $source->getEffectiveToken();

// Priority:
// 1. $source->gitAccountToken->getToken()
// 2. $source->getToken() (encrypted_token field)
// 3. config('services.github.token')
```
