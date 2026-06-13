# Grocery API Architecture

## Style
Modular monolith with domain-oriented folders under `app/Domain` and API endpoints under `app/Http/Controllers/Api/V1`.

## Modules
- Auth: JWT access tokens + refresh token rotation.
- Catalog: categories/products with admin approval.
- Cart: active cart, favorites, saved-for-later.
- Orders: checkout, history, and status timeline.
- Payments: payment intents, installment support, escrow and payouts.
- Delivery: shipment and shipment events.
- Notifications: queued jobs and email logs.

## Infra Targets (GCP)
- Compute: Cloud Run.
- Database: Cloud SQL for MySQL.
- Cache and queue: Memorystore (Redis).
- Files: Cloud Storage.
- Secrets: Secret Manager.
- Logs/metrics: Cloud Logging + Monitoring.
