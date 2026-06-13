# Security Baseline

## Auth and Authorization
- JWT guard for API endpoints.
- Refresh token rotation with revocation table.
- Spatie roles/permissions with `user`, `vendor`, `admin` roles.
- Verified-email middleware for sensitive routes.

## API Security
- Route throttling for login and general API usage.
- Idempotency key required for payment intent creation.
- Server-side validation for all write operations.
- Audit trail using order status history and email logs.

## Operational Security
- Use HTTPS-only in production.
- Keep secrets in Secret Manager, never in repo.
- Verify payment webhooks with signature validation before state changes.
- Add WAF/rate policies at GCP load balancer layer for production.
