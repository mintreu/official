<?php

namespace App\Models;

use App\Enums\SourceProvider;
use App\Models\Products\ProductSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

/**
 * GitAccountToken - Secure storage for Git provider PATs
 *
 * Supports multiple accounts per provider (e.g., different GitHub orgs)
 * Tokens are encrypted at rest using Laravel's encryption.
 *
 * @property int $id
 * @property string $name Display name
 * @property string $provider github, gitlab, bitbucket
 * @property string|null $account_identifier Username/org for reference
 * @property string $encrypted_token Encrypted PAT
 * @property array|null $scopes Token scopes
 * @property string|null $notes Admin notes
 * @property bool $is_active
 * @property \Carbon\Carbon|null $expires_at
 * @property \Carbon\Carbon|null $last_used_at
 * @property \Carbon\Carbon|null $last_verified_at
 */
class GitAccountToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'account_identifier',
        'encrypted_token',
        'scopes',
        'notes',
        'is_active',
        'expires_at',
        'last_used_at',
        'last_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'scopes' => 'array',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
            'last_verified_at' => 'datetime',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Product sources using this token
     */
    public function productSources(): HasMany
    {
        return $this->hasMany(ProductSource::class);
    }

    // ===== TOKEN ENCRYPTION =====

    /**
     * Get decrypted token
     */
    public function getToken(): string
    {
        return Crypt::decryptString($this->encrypted_token);
    }

    /**
     * Set encrypted token
     */
    public function setToken(string $token): void
    {
        $this->encrypted_token = Crypt::encryptString($token);
    }

    /**
     * Create with encrypted token (static factory helper)
     */
    public static function createWithToken(array $attributes, string $token): self
    {
        $attributes['encrypted_token'] = Crypt::encryptString($token);

        return self::create($attributes);
    }

    // ===== TOKEN VERIFICATION =====

    /**
     * Verify token is still valid with the provider
     */
    public function verify(): bool
    {
        $isValid = match ($this->provider) {
            'github' => $this->verifyGitHubToken(),
            'gitlab' => $this->verifyGitLabToken(),
            'bitbucket' => $this->verifyBitbucketToken(),
            default => false,
        };

        if ($isValid) {
            $this->update(['last_verified_at' => now()]);
        }

        return $isValid;
    }

    private function verifyGitHubToken(): bool
    {
        $response = Http::withToken($this->getToken())
            ->withHeaders([
                'Accept' => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
            ])
            ->get('https://api.github.com/user');

        return $response->successful();
    }

    private function verifyGitLabToken(): bool
    {
        $response = Http::withHeaders(['PRIVATE-TOKEN' => $this->getToken()])
            ->get('https://gitlab.com/api/v4/user');

        return $response->successful();
    }

    private function verifyBitbucketToken(): bool
    {
        // Bitbucket uses app passwords with basic auth
        $response = Http::withBasicAuth($this->account_identifier ?? '', $this->getToken())
            ->get('https://api.bitbucket.org/2.0/user');

        return $response->successful();
    }

    /**
     * Mark token as used
     */
    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    // ===== SCOPES =====

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if token is usable (active and not expired)
     */
    public function isUsable(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    // ===== QUERIES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeForAccount($query, string $identifier)
    {
        return $query->where('account_identifier', $identifier);
    }

    /**
     * Get active token for a specific provider and account
     */
    public static function getFor(string $provider, ?string $accountIdentifier = null): ?self
    {
        $query = self::active()->forProvider($provider);

        if ($accountIdentifier) {
            $query->forAccount($accountIdentifier);
        }

        return $query->first();
    }

    /**
     * Get provider enum
     */
    public function getProviderEnum(): SourceProvider
    {
        return SourceProvider::from($this->provider);
    }
}
