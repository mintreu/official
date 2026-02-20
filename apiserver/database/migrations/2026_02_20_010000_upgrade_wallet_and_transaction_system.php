<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table): void {
            if (! Schema::hasColumn('wallets', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('wallets', 'currency')) {
                $table->string('currency', 3)->default('INR')->after('total_debited_paise');
            }
            if (! Schema::hasColumn('wallets', 'status')) {
                $table->string('status')->default('active')->after('currency');
            }
            if (! Schema::hasColumn('wallets', 'points')) {
                $table->unsignedBigInteger('points')->default(0)->after('status');
            }
            if (! Schema::hasColumn('wallets', 'pin')) {
                $table->string('pin')->nullable()->after('points');
            }
            if (! Schema::hasColumn('wallets', 'pin_updated_at')) {
                $table->timestamp('pin_updated_at')->nullable()->after('pin');
            }
            if (! Schema::hasColumn('wallets', 'metadata')) {
                $table->json('metadata')->nullable()->after('pin_updated_at');
            }
            if (! Schema::hasColumn('wallets', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('transactions', function (Blueprint $table): void {
            if (! Schema::hasColumn('transactions', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('transactions', 'wallet_id')) {
                $table->foreignId('wallet_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('transactions', 'transactionable_type')) {
                $table->string('transactionable_type')->nullable()->after('wallet_id');
            }
            if (! Schema::hasColumn('transactions', 'transactionable_id')) {
                $table->unsignedBigInteger('transactionable_id')->nullable()->after('transactionable_type');
            }
            if (! Schema::hasColumn('transactions', 'amount')) {
                $table->unsignedBigInteger('amount')->default(0)->after('status');
            }
            if (! Schema::hasColumn('transactions', 'fee')) {
                $table->unsignedBigInteger('fee')->default(0)->after('amount');
            }
            if (! Schema::hasColumn('transactions', 'tax')) {
                $table->unsignedBigInteger('tax')->default(0)->after('fee');
            }
            if (! Schema::hasColumn('transactions', 'net_amount')) {
                $table->unsignedBigInteger('net_amount')->default(0)->after('tax');
            }
            if (! Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency');
            }
            if (! Schema::hasColumn('transactions', 'checkout_type')) {
                $table->string('checkout_type')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('transactions', 'integration_id')) {
                $table->foreignId('integration_id')->nullable()->after('checkout_type')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('transactions', 'provider_order_id')) {
                $table->string('provider_order_id')->nullable()->after('integration_id');
            }
            if (! Schema::hasColumn('transactions', 'provider_transaction_id')) {
                $table->string('provider_transaction_id')->nullable()->after('provider_order_id');
            }
            if (! Schema::hasColumn('transactions', 'provider_signature')) {
                $table->string('provider_signature')->nullable()->after('provider_transaction_id');
            }
            if (! Schema::hasColumn('transactions', 'provider_gen_id')) {
                $table->string('provider_gen_id')->nullable()->after('provider_signature');
            }
            if (! Schema::hasColumn('transactions', 'provider_gen_session')) {
                $table->string('provider_gen_session')->nullable()->after('provider_gen_id');
            }
            if (! Schema::hasColumn('transactions', 'provider_gen_link')) {
                $table->string('provider_gen_link')->nullable()->after('provider_gen_session');
            }
            if (! Schema::hasColumn('transactions', 'provider_gen_qr')) {
                $table->string('provider_gen_qr')->nullable()->after('provider_gen_link');
            }
            if (! Schema::hasColumn('transactions', 'provider_generated_sign')) {
                $table->string('provider_generated_sign')->nullable()->after('provider_gen_qr');
            }
            if (! Schema::hasColumn('transactions', 'qr_code_url')) {
                $table->string('qr_code_url')->nullable()->after('provider_generated_sign');
            }
            if (! Schema::hasColumn('transactions', 'success_url')) {
                $table->string('success_url')->nullable()->after('qr_code_url');
            }
            if (! Schema::hasColumn('transactions', 'failure_url')) {
                $table->string('failure_url')->nullable()->after('success_url');
            }
            if (! Schema::hasColumn('transactions', 'verified')) {
                $table->boolean('verified')->default(false)->after('failure_url');
            }
            if (! Schema::hasColumn('transactions', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified');
            }
            if (! Schema::hasColumn('transactions', 'description')) {
                $table->string('description')->nullable()->after('verified_at');
            }
            if (! Schema::hasColumn('transactions', 'purpose')) {
                $table->string('purpose')->nullable()->after('description');
            }
            if (! Schema::hasColumn('transactions', 'notes')) {
                $table->text('notes')->nullable()->after('purpose');
            }
            if (! Schema::hasColumn('transactions', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('transactions', 'parent_transaction_id')) {
                $table->foreignId('parent_transaction_id')->nullable()->after('reference_number')->constrained('transactions')->nullOnDelete();
            }
            if (! Schema::hasColumn('transactions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('parent_transaction_id');
            }
            if (! Schema::hasColumn('transactions', 'balance_after')) {
                $table->unsignedBigInteger('balance_after')->nullable()->after('expires_at');
            }
            if (! Schema::hasColumn('transactions', 'metadata')) {
                $table->json('metadata')->nullable()->after('balance_after');
            }
            if (! Schema::hasColumn('transactions', 'provider_response')) {
                $table->json('provider_response')->nullable()->after('metadata');
            }
            if (! Schema::hasColumn('transactions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        // Keep down() non-destructive because these columns may hold production payment history.
    }
};
