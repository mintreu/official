# TODO (Official)

1. Setup .codex knowledge + docs indexing
   - Add project summary + architecture notes
   - Track confirmations before marking fixed

2. Auth + user model
   - Confirm auth flows (register/login, email/mobile)
   - Ensure user table supports referral code + referral tracking

3. Payment + purchase flow (reuse from commerinity)
   - Port Transaction/PaymentService/HasTransaction + Cashfree integration
   - Add purchase endpoints for plan subscriptions
   - Webhook + verify -> license + api key issuance

4. Referral (not MLM)
   - Referral code generation
   - Track referred sales + commission payouts

5. Sales + vouchers
   - Cart not needed; apply voucher/discount in checkout
   - Add voucher validation rules (old_project parity if needed)

6. Orders (digital)
   - Order record + payment status + refund
   - No shipping; digital delivery (download/access)

7. License + subscription tracking
   - Plan purchase -> license -> api key
   - Renewal/cancel/expiry handling

8. Helpdesk + FAQ
   - Admin FAQ + public FAQ
   - Helpdesk tickets + responses

9. Client UX
   - Product purchase flow UI
   - Download access gating + license modal
   - Account dashboard (licenses, downloads, api keys, invoices)

10. Tests
   - Payment, purchase, license, download
   - Referral commission tests
