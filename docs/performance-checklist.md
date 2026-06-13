# Performance Checklist

## Database
- Use composite indexes for order/product/payment hot paths.
- Keep transactions short around checkout and payment capture.
- Use row-level locking where balance mutation occurs.

## Caching
- Cache product/category lists in Redis.
- Cache policy/permission maps per user session.

## Queueing
- Move emails, payout release batches, and notifications to queues.
- Run multiple queue workers in production.

## API
- Paginate list endpoints.
- Avoid N+1 via eager loading.
- Log slow queries and tune indexes regularly.

## GCP
- Enable autoscaling for Cloud Run service.
- Use separate read replicas when query throughput grows.
- Add uptime checks and SLO alerting.
