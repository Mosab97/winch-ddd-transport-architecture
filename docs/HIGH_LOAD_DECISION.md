# High Write Load And Active Orders Decision

The high-load scenario has two different pressures that should not be solved with one tool only: very high write volume into `orders`, and slow reads for the active-orders screen. I would start with the lowest-risk database changes, then introduce a dedicated read model if the load keeps growing.

The first step is adding the right indexes. For the current API, useful indexes would target status filtering, driver-order lookups, and active assigned orders. Indexes are safe, simple, and keep the source of truth unchanged. They help reads such as active orders and driver orders, and they can also reduce the cost of existence checks during assignment. The trade-off is that indexes slow writes slightly because every insert or update must maintain them. With millions of incoming orders, indexes are necessary but not sufficient; they improve query access patterns, but they do not isolate the dashboard from the hot write table.

Redis can help when the same active-order result is read repeatedly. It can reduce database load and make the dashboard faster. The risk is consistency. Active orders change frequently, so cached data can become stale unless invalidation is carefully designed. Redis is a good optimization for read-heavy views, counters, or short-lived snapshots, but I would not make it the primary source of truth for assignment state.

For sustained growth, I would introduce a separate active-orders read model/table. The main `orders` table remains the write source of truth, while an `active_orders` table contains only rows needed by the operational screen. This keeps dashboard reads fast and predictable because they scan a much smaller dataset. The trade-off is extra complexity: the read model must be kept in sync when orders are created, assigned, completed, or cancelled. That can be done synchronously inside the same transaction at first, then moved to events or queues if needed.

So my path would be: start with indexes, measure, then add an active-orders read model for isolation. Redis can sit on top later for caching snapshots, but not as the first correctness mechanism.
