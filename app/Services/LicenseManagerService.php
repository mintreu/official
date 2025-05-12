<?php

namespace App\Services;

use App\Models\Order\Subscription;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class LicenseManagerService
{

    protected User $user;
    protected Product $product;
    protected Subscription $subscription;
    protected string $licence;
    protected string $secret;
    protected bool $openSslEncryption = false;

    public function __construct()
    {
        $this->secret = config('license_secret.secret_key');
        $this->openSslEncryption = true;
    }


    public static function make(): static
    {
        return new static();
    }


    public function user(User $user): static
    {
        $this->user = $user;
        return $this;
    }


    public function product(Product $product): static
    {
        $this->product = $product;
        return $this;
    }


    public function subscription(Subscription $subscription): static
    {
        $this->subscription = $subscription;
        return $this;
    }


    public function generate():static
    {
        $this->licence = $this->compile();

        return $this;
    }

    public function getLicense():string
    {
        return $this->licence;
    }


    public function validate(string $license): bool
    {
        $decompiledData = $this->decompile($license);

        if (isset($decompiledData['error'])) {
            return false;
        }

        return now()->lte($decompiledData['expire_on']); // Ensure license is not expired
    }



    private function compile()
    {
        $data = [
            'client' => $this->user->email,
            'product' => $this->product->name,
            'expire_on' => now()->addDays(60)->toDateTimeString(),
            'issued_on' => now()->toDateTimeString(),
            'unique_id' => uniqid($this->product->id, true), // Add a unique identifier
            'random_padding' => bin2hex(random_bytes(800)), // Add 1600 random characters
        ];

        // Serialize the data into a JSON string
        $serializedData = json_encode($data);

        // Add a secret text for additional security
        $secretText = $this->secret; // Replace this with a secure value
        $combinedData = $serializedData . '||' . $secretText;


        // Encrypt the combined data using Laravel's Crypt
        $encryptedData = Crypt::encrypt($combinedData);

        // Encode the encrypted data for safe storage/transmission
        return base64_encode($encryptedData);
    }

    public function decompile($license)
    {
        try {
            // Decode the encrypted license
            $decodedData = base64_decode($license);

            // Decrypt the data using Laravel's Crypt
            $combinedData = Crypt::decrypt($decodedData);

            // Separate the data and secret text
            [$decryptedData, $secretText] = explode('||', $combinedData, 2);

            // Verify the secret text
            if ($secretText !== $this->secret) {
                throw new \Exception('Invalid secret key');
            }

            // Deserialize the JSON string back to an array
            return json_decode($decryptedData, true);
        } catch (\Exception $e) {
            // Handle errors such as invalid license or decryption failures
            return ['error' => 'Invalid license or failed decryption'];
        }
    }



}
