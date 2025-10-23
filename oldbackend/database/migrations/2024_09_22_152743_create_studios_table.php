<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('url')->unique(); // unique studio id / url identifier

            // Domain/URL for frontend integration (optional)
            $table->string('domain')->nullable(); // frontend or studio app domain

            // Studio API Access Credentials
            $table->string('key')->nullable();      // public key or client_id
            $table->string('secret')->nullable();   // private key or secret
            $table->text('serial')->nullable();     // could be license key or hashed auth token

            // Subscription / Licensing
            $table->timestamp('expire_on')->nullable(); // expiration date
            $table->boolean('is_active')->default(true); // manually activate/deactivate
            $table->boolean('is_trial')->default(false); // trial period flag
            $table->timestamp('trial_ends_at')->nullable(); // end of trial

            // App Type & Versioning
            $table->string('channel')->nullable();
            $table->string('version')->nullable(); // optional, version of deployed client
            $table->string('platform')->nullable(); // like 'Windows', 'iOS', 'Android'

            // Ownership
            $table->morphs('host'); // host_id, host_type (User, Team, etc.)

            // Subscription Info
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->cascadeOnUpdate()->nullOnDelete();

            // Extra configuration / metadata
            $table->json('metadata')->nullable();

            $table->string('status')->nullable();
            $table->text('status_feedback')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
