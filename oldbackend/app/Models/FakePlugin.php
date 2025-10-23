<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FakePlugin extends Model
{
    protected $table = 'f_plugins'; // can be fake
    public $exists = true;
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'slug', 'version', 'src_path', 'status', 'type',
    ];

    protected static ?Collection $mockedCollection = null;

    public function getKey()
    {
        return $this->id;
    }

    // Laravel will call this on model boot
    protected static function booted(): void
    {
        static::$mockedCollection = new Collection(self::getFakePlugins());
    }

    // Static accessor to mocked collection
    public static function mocked(): Collection
    {
        static::$mockedCollection = new Collection(self::getFakePlugins());
        return static::$mockedCollection;
    }

    // Your static fake plugins
    public static function getFakePlugins(): array
    {
        return [
            new self([
                'id' => 9991,
                'name' => 'PayPal SDK',
                'slug' => 'paypal-sdk',
                'version' => '1.0.0',
                'src_path' => 'https://github.com/paypal/Checkout-PHP-SDK.git',
                'status' => 'not_installed',
                'type' => 'standalone',
            ]),
            new self([
                'id' => 9992,
                'name' => 'DOMPDF',
                'slug' => 'dompdf',
                'version' => '3.1.0',
                'src_path' => 'https://github.com/dompdf/dompdf/releases/download/v3.1.0/dompdf-3.1.0.zip',
                'status' => 'not_installed',
                'type' => 'batch',
            ]),
            new self([
                'id' => 9993,
                'name' => 'PHPWord',
                'slug' => 'phpword',
                'version' => '1.4.0',
                'src_path' => 'https://github.com/PHPOffice/PHPWord/archive/refs/tags/1.4.0.zip',
                'status' => 'not_installed',
                'type' => 'batch',
            ]),
        ];
    }
}
