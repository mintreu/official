# Minimal sequence plan (Official)

1. Baseline audit
   - Confirm existing auth/user model + referral fields
   - Map current product/plan/license models

2. Payment core
   - Port HasTransaction + Transaction + PaymentService + Cashfree provider
   - Add checkout route + Livewire checkout
   - Verify webhook/verify flow works

3. Purchase flow
   - Create purchase endpoint for plans
   - On PaymentCompleted: issue License + ApiKey
   - Add basic order/receipt record (digital)

4. Referral tracking
   - Track referred users + purchase attribution
   - Payout logic (wallet optional)

5. Vouchers
   - Port voucher validation rules (no shipping)
   - Apply in purchase endpoint

6. Helpdesk + FAQ
   - Port models/controllers/filament

7. Client UX
   - Purchase buttons -> checkout redirect
   - License dashboard + download access

8. Tests
   - Payment -> license issuance
   - Referral commission
   - Voucher validation
   - Download token flow

Only mark done after user confirmation.
