# Architecture Notes

## DDD-Inspired Structure

The project keeps transport concepts under `src/Domain` and HTTP concerns under `src/Presentation`. Orders own order state, Drivers own driver state, and Dispatch coordinates assignment behavior that crosses both domains.

## Thin Controllers

Controllers only accept requests and return resources. They delegate decisions to actions and services so business rules can be tested without HTTP details.

## Actions, Services, And Contracts

Actions represent application use cases, such as assigning an order or listing driver orders. Services hold reusable domain behavior, such as distance calculation and assignment validation. Contracts are used for cross-domain services so dependencies can be replaced without changing controllers or actions.

## Concurrency Handling

Assignment runs inside a database transaction. The order row is locked with `lockForUpdate()`, its status is checked inside the transaction, candidate drivers are selected by availability and distance, and each candidate driver is locked and re-checked before the assignment is saved.

This prevents the same order from being assigned twice and prevents a driver with an active assigned order from receiving another active order.

## Database Choice

PostgreSQL is the default because it works well with Laravel Sail and supports reliable row-level locking. The schema avoids PostgreSQL-only column types so the app can still be adapted to MySQL with minimal changes.

## Row-Level Locking Trade-Offs

Row-level locks are simple and reliable for this task, but they can reduce throughput when many requests target the same orders or drivers. The locked section is intentionally short and avoids external calls, notifications, or slow work.

## Future Improvements

- Move distance sorting into a database geospatial query for larger driver pools.
- Add authentication and authorization.
- Add explicit order completion logic that frees the driver.
- Add observability around assignment conflicts and lock wait times.
- Add a query filter object if driver order filtering grows beyond status and pagination.
