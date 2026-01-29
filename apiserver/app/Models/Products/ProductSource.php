<?php

namespace App\Models\Products;

use App\Enums\SourceProvider;
use App\Models\GitAccountToken;
use App\Models\Product;
use Database\Factories\ProductSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

/**
 * ProductSource - Single download source for a product
 *
 * Replaces the over-engineered ProductConfig + ProductAsset + StorageProvider + StorageCredential
 *
 * One product can have multiple sources (e.g., different versions, platforms)
 * Each source = one downloadable file/release
 *
 * @property int $id
 * @property int $product_id
 * @property SourceProvider $provider
 * @property string $name
 * @property string|null $description
 * @property string $source_url Real download URL (private, never exposed)
 * @property string|null $encrypted_token Auth token for private repos (encrypted) - DEPRECATED, use git_account_token_id
 * @property int|null $git_account_token_id Reference to GitAccountToken
 * @property string|null $version Version tag (e.g., "v1.2.0")
 * @property string|null $file_name Expected filename for download
 * @property int|null $file_size File size in bytes (for display)
 * @property array|null $metadata Extra provider-specific data (JSON)
 * @property bool $is_primary Primary source shown first
 * @property bool $is_active Source available for download
 * @property \Carbon\Carbon|null $last_verified_at Last time URL was verified working
 */
class ProductSource extends Model
{
    /** @use HasFactory<ProductSourceFactory> */
    use HasFactory;

    protected static function newFactory(): ProductSourceFactory
    {
        return ProductSourceFactory::new();
    }

    protected $fillable = [
        'product_id',
        'provider',
        'name',
        'description',
        'source_url',
        'encrypted_token',
        'git_account_token_id',
        'version',
        'file_name',
        'file_size',
        'metadata',
        'is_primary',
        'is_active',
        'last_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'provider' => SourceProvider::class,
            'metadata' => 'json',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'file_size' => 'integer',
            'last_verified_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Git account token (for multi-account support)
     */
    public function gitAccountToken(): BelongsTo
    {
        return $this->belongsTo(GitAccountToken::class);
    }

    /**
     * Get auth token with fallback chain:
     * 1. GitAccountToken (preferred - multi-account)
     * 2. encrypted_token (legacy - per-source)
     * 3. config token (fallback - global)
     */
    public function getEffectiveToken(): ?string
    {
        // 1. Check linked GitAccountToken
        if ($this->git_account_token_id && $this->gitAccountToken?->isUsable()) {
            $this->gitAccountToken->markUsed();

            return $this->gitAccountToken->getToken();
        }

        // 2. Check legacy encrypted_token on source
        if ($this->encrypted_token) {
            return Crypt::decryptString($this->encrypted_token);
        }

        // 3. Fall back to config token
        return match ($this->provider->value) {
            'github' => config('services.github.token'),
            'gitlab' => config('services.gitlab.token'),
            'bitbucket' => config('services.bitbucket.password'),
            default => null,
        };
    }

    /**
     * Get decrypted auth token (legacy - use getEffectiveToken instead)
     */
    public function getToken(): ?string
    {
        if (! $this->encrypted_token) {
            return null;
        }

        return Crypt::decryptString($this->encrypted_token);
    }

    /**
     * Set encrypted auth token
     */
    public function setToken(?string $token): void
    {
        $this->encrypted_token = $token ? Crypt::encryptString($token) : null;
    }

    /**
     * Check if source URL is private (should be masked)
     */
    public function isPrivate(): bool
    {
        return $this->provider->isPrivate();
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeFormatted(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2).' '.$units[$unit];
    }

    /**
     * Scope: only active sources
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: primary source first
     */
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_primary')->orderBy('name');
    }
}
