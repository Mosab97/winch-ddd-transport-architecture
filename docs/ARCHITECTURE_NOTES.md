# Architecture Notes

## Overview

This project uses a DDD-inspired Laravel structure for a transport dispatch assignment system. The goal is to keep the business rules for assigning orders to drivers easy to read, test, and change without mixing them into HTTP controllers or Vue components.

The implementation is intentionally pragmatic. It is not a full enterprise DDD implementation, but it separates the most important boundaries for this assignment:

- `Domain` contains business concepts, use cases, services, contracts, enums, and domain exceptions.
- `Presentation` contains API controllers, form requests, resources, routes, response formatting, and exception rendering.
- Vue lives in Laravel's frontend asset pipeline and consumes the API as a same-origin client.

## DDD-Inspired Structure

The code is organized around the business areas of the system:

- `Orders` owns order state and order status.
- `Drivers` owns driver state, availability, and driver-order relationships.
- `Dispatch` owns assignment behavior, best-driver selection, distance calculation, and concurrency protection.

This structure keeps transport assignment logic close to the domain language instead of spreading it across controllers, routes, or generic helper classes.

## Actions

Actions represent application use cases. For example, assigning an order is not just a database update; it is a full workflow:

- lock the order row
- verify the order is still pending
- find candidate drivers
- lock candidate driver rows
- verify the driver is still available
- assign the order
- mark the driver busy
- return the updated order

Keeping this workflow in an Action makes controllers thin and makes the use case easier to test and reason about.

## Services

Services hold reusable domain behavior that is more specific than a generic model method but smaller than a full use case.

Examples:

- `FindBestAvailableDriverService` finds eligible drivers and sorts them by distance.
- `DistanceCalculatorService` calculates Haversine distance between coordinates.
- `AssignOrderToDriverService` performs the actual assignment state changes after the workflow has selected a driver.

This keeps domain operations explicit and avoids hiding important business behavior inside controllers.

## Contracts

Contracts are used for the dispatch services that represent important business capabilities:

- finding the best available driver
- assigning an order to a driver
- calculating distance between coordinates

Even small stateless domain services use contracts here to respect the assignment's domain-boundary rule. The current implementation binds those contracts to local services, but the boundary makes the design easier to extend. For example, driver selection could later use an external routing service, a queue, or a more advanced scoring algorithm without changing the controller layer.

## PostgreSQL

PostgreSQL is a strong fit for this assignment because the system needs reliable transactional behavior. Assignment is a consistency-sensitive operation: two requests should not assign the same order or the same available driver at the same time.

PostgreSQL gives the project solid transaction support, row-level locking, predictable constraints, and good behavior under concurrent writes.

## Concurrency Protection

The assignment flow is protected with a database transaction and `lockForUpdate`.

The order row is locked before checking whether it is still pending. Candidate driver rows are also locked before assignment. This prevents two concurrent assignment requests from both seeing the same order or driver as available and writing conflicting results.

The service also re-checks driver availability before assigning:

- driver status must still be `available`
- driver must not already have an active assigned order

If a candidate becomes unavailable during the transaction, the workflow skips that driver and tries the next candidate.

## API Presentation

API controllers stay thin. Their job is to receive validated requests, call an Action, and return a resource or response envelope.

Responses are centralized through `ApiResponse`, which keeps success, paginated, and error responses consistent:

- success responses include `status`, `message`, `data`, and `code`
- paginated responses also include `meta` and `links`
- error responses use the same envelope and may include validation `errors`

Domain exceptions are rendered through the presentation exception layer so business failures become clean API responses.

## Enums

Order and driver statuses are represented with PHP enums. This avoids scattering raw strings across the codebase and keeps validation, persistence casting, and business checks consistent.

The main statuses are:

- order: `pending`, `assigned`, `completed`, `cancelled`
- driver: `available`, `busy`, `offline`

## Vue Inside Laravel

Vue is embedded inside Laravel because this assignment benefits from a simple same-origin frontend without adding deployment complexity.

The Vue app provides:

- orders list
- status filtering
- assignment action
- drivers list
- driver orders list
- loading, empty, error, and success states

The frontend calls Laravel through `/api/*` endpoints. API calls are centralized in service files, while Vue composables manage loading state, errors, filters, pagination, and assignment state.

This keeps Vue components focused on rendering and user interaction.

## Trade-Offs

This implementation keeps the architecture practical for the assignment size.

The project does not introduce a separate repository layer because Eloquent is already expressive enough for the current queries. The domain still uses Eloquent models, which is a pragmatic Laravel trade-off rather than strict persistence isolation.

The best-driver selection currently loads eligible drivers and sorts them in PHP using Haversine distance. This is clear and testable for the expected dataset. For very large datasets, the next step would be database-level geospatial indexing or a dedicated routing service.

The Vue frontend is intentionally simple. It does not use a global store because the current state is page-level and fits cleanly inside composables.

The written high-load decision requested by the assignment is documented separately in `docs/HIGH_LOAD_DECISION.md`.

## Large Driver Dataset Considerations

The current driver selection approach is correct and easy to understand, but it is optimized for a small-to-medium number of available drivers.

At the moment, eligible drivers are loaded, distance is calculated for each driver, and the candidates are sorted in PHP. If the number of available drivers becomes very large, this can create several issues:

- higher memory usage because many Eloquent models are loaded at once
- slower assignment requests because distance calculation and sorting happen in application memory
- longer database transactions because the order lock is held while the candidate list is prepared and tested
- more lock contention under concurrent assignment requests

The transaction and `lockForUpdate` usage still protect correctness, so the main risk is performance and throughput rather than duplicate assignment.

For a production-scale driver pool, the candidate selection should move closer to the database. Possible improvements include:

- adding indexes for driver status and active order lookups
- limiting candidates before sorting, for example by using a geographic bounding box around the pickup location
- returning only the nearest N candidates instead of loading all available drivers
- using PostgreSQL geospatial features or PostGIS for distance-based querying
- keeping the transaction as short as possible by reducing work done after locks are acquired

This would preserve the same assignment behavior while making the system more scalable for a large fleet.

## Final Review Notes

The current architecture keeps the main business rules out of controllers, protects assignment from obvious race conditions, returns consistent API responses, and keeps frontend API access out of Vue components.
