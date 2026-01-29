# Database Schema (Marketplace Core)

This document summarizes the current marketplace-related schema. Migrations are the source of truth.

## Users
- id, name, email, password, email_verified_at, about, remember_token, timestamps

## Products
- id, slug, title, description, content, image
- short_description, price, category, type, version
- demo_url, github_url, documentation_url
- downloads, rating, status, featured
- requires_auth, default_license
- meta (json), api_config (json)
- timestamps

## Product Sources
- product_id (FK)
- provider, name, description
- source_url, encrypted_token
- version, file_name, file_size
- metadata (json)
- is_primary, is_active
- last_verified_at, timestamps

## Licenses
- product_id, user_id
- type, usage_count, max_usage
- meta (json), server_info (json)
- api_credential_id (FK), plan_id (FK)
- expires_at, is_active, first_used_at, last_used_at
- timestamps

## Download Logs
- product_id, product_source_id, user_id, license_id
- ip_address, user_agent, status
- download_token, file_size, downloaded_at
- timestamps

## External API Credentials
- product_id, user_id
- encrypted_api_key, encrypted_api_secret
- external_user_id, external_account_url
- plan_id, rate_limits (json), usage_stats (json), meta (json)
- is_active, last_synced_at, expires_at
- timestamps

## Plans (API / Subscription)
- product_id, slug, name, description
- price_cents, billing_cycle
- requests_per_month, requests_per_day, requests_per_minute
- features (json), limits (json)
- sort_order, is_popular, is_active
- timestamps

## API Endpoints
- product_id, method, path, name, description
- base_url, weight, is_public, is_active
- params (json), response (json)
- timestamps

## API Keys
- product_id, license_id, plan_id, user_id
- key_hash, key_prefix, name
- domain_restriction, ip_whitelist (json)
- requests_this_month, requests_today
- last_used_at, expires_at, is_active
- timestamps

## API Usage Logs
- api_key_id, api_endpoint_id
- method, path, status_code, response_time_ms
- request_size, response_size
- ip_address, user_agent, referer, country
- request_headers (json), error_details (json)
- created_at

## Advertisements
- name, placement, html_code, allowed_pages
- priority, impressions, clicks, unique_ips, viewed_ips
- max_impressions_per_ip, is_active
- starts_at, ends_at
- timestamps

## Marketing Content (Legacy / Site Content)
- projects
- case_studies
- services

Note: If any conflict appears between this document and migrations, migrations win.
