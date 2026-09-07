# Capability Map: IT Helpdesk & Ticketing System

## Module Overview

| Module ID | Responsibility | Depends On |
|-----------|---------------|------------|
| `identity` | User registration, authentication, role management (Admin, Agent, User) | — |
| `tickets` | Ticket CRUD, status workflow, assignment, priority, categories | `identity` |
| `dashboard` | Agent dashboard, user portal, ticket lists, search/filter | `tickets` |
| `notifications` | Email notifications on ticket events (created, assigned, updated) | `identity`, `tickets` |

## Build Order

```
identity → tickets → dashboard → notifications
```

## Phase Grouping

### Phase 1: Foundation
- `identity` — auth, roles, user management

### Phase 2: Core
- `tickets` — ticket lifecycle

### Phase 3: Interface
- `dashboard` — agent & user views

### Phase 4: Polish
- `notifications` — email alerts

## Module Boundaries

- `identity` provides: `User` model, role enum, auth middleware, `has-role` directive
- `tickets` consumes: `User` model for assignment/ownership
- `dashboard` consumes: `Ticket` query scopes, `User` roles for view filtering
- `notifications` consumes: `Ticket` events, `User` email addresses
