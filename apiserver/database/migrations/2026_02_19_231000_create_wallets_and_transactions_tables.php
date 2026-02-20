<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance_paise')->default(0);
            $table->unsignedBigInteger('hold_balance_paise')->default(0);
            $table->unsignedBigInteger('total_credited_paise')->default(0);
            $table->unsignedBigInteger('total_debited_paise')->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_id')->nullable()->constrained('licenses')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('type', 20); // credit | debit
            $table->string('status', 20)->default('pending'); // pending | completed | failed
            $table->unsignedBigInteger('amount_paise');
            $table->unsignedBigInteger('balance_after_paise')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('reference')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
    }
};

