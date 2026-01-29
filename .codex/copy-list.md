# Reuse/Copy list from Commerinity -> Official

Goal: minimal effort porting for digital SaaS marketplace.

## Payment + Transaction (core)
Copy from commerinity/apiserver:
- app/Traits/HasTransaction.php
- app/Models/Transaction.php
- app/Services/IntegrationServices/Payment/* (PaymentService, DTOs, Providers)
- app/Listeners/Payment/HandlePaymentCompleted.php (adapt for official)
- app/Http/Controllers/Api/CheckoutController.php
- app/Http/Controllers/Api/Webhooks/CashfreeWebhookController.php
- routes/web.php checkout route (Livewire checkout)
- app/Livewire/Checkout/* + resources/views/livewire/checkout/*
- config/services.php (payment config)
- database/seeders/IntegrationSeeder.php
- app/Models/Integration.php

Adapt in official:
- PaymentCompleted should create License + ApiKey (for paid plans)
- For digital purchases, no shipping; order record optional but recommended

## Voucher/Sale validation (digital)
- app/Services/Ecommerce/CartService/CartVoucherValidator.php
- app/Services/Ecommerce/CartService/CartSaleValidator.php
- app/Services/Ecommerce/CartService/CartService.php (only if needed; for digital can skip cart)
- config/sales.php or config/laravel-commerinity sales targets (port minimal)

Adapt:
- Apply voucher on plan purchase (no shipping) + license amount

## Refunds
- Cashfree webhook refund handling in CashfreeWebhookController
- Transaction status updates + RefundProcessed event (if exists)

## Helpdesk/FAQ
- app/Models/Helpdesk*, Faq models
- app/Http/Controllers/Api/FaqController.php
- app/Http/Controllers/Api/TicketController.php
- Filament resources for FAQ + Helpdesk
- database/seeders/Helpdesk* and Faq seeders

## Notifications
- app/Notifications/* (subscription/payment/order)
- app/Http/Controllers/Api/Notification/*
- database tables for notifications (if missing)

## Referral (non-MLM)
- Use simple referral tracking (referral_code, referred_by_id)
- Track purchases by referred users
- Commission payout: wallet/transaction module can be reused

## Wallet (optional)
If payouts to referrers:
- app/Models/Wallet + wallet services
- WalletController (withdraw, beneficiaries, OTP)
- Payout integrations (Cashfree Payout)

## Tests
- payment flow tests (wallet optional)
- license creation on payment completion
- voucher validation on plan purchase
- referral commission tests

## Client (Nuxt)
Copy/port concepts from commerinity client:
- checkout redirect flow
- payment success/failure handling
- account dashboard sections (licenses, downloads)

NOTE: do not mark any port “done” until user confirms.
