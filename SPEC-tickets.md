# Spec: Tickets Module

## Objective

Implement the core ticketing workflow: create, assign, track, and resolve support tickets with status transitions, priority levels, and categories.

## Database Schema

### tickets

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| title | varchar(255) | required |
| description | text | required, markdown-safe |
| status | enum | open, in_progress, resolved, closed |
| priority | enum | low, medium, high, urgent |
| category_id | foreign key | references categories.id |
| created_by | foreign key | references users.id |
| assigned_to | foreign key | nullable, references users.id |
| resolved_at | timestamp | nullable |
| closed_at | timestamp | nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

### categories

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| name | varchar(100) | unique |
| description | varchar(255) | nullable |
| is_active | boolean | default: true |
| created_at | timestamp | |
| updated_at | timestamp | |

### comments

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| ticket_id | foreign key | references tickets.id, cascade delete |
| user_id | foreign key | references users.id |
| body | text | required |
| is_internal | boolean | agent-only notes, default: false |
| created_at | timestamp | |
| updated_at | timestamp | |

## Ticket Status Workflow

```
         ┌──────────┐
         │   OPEN   │ ◄─── user creates ticket
         └────┬─────┘
              │ agent picks up
              ▼
      ┌───────────────┐
      │  IN_PROGRESS  │
      └───────┬───────┘
              │ agent resolves
              ▼
      ┌───────────┐
      │  RESOLVED │ ◄─── user can reopen within 7 days
      └─────┬─────┘
            │ auto-close after 7 days OR user confirms
            ▼
      ┌─────────┐
      │  CLOSED │
      └─────────┘
```

**Transition rules:**
- `open → in_progress`: Agent or Admin assigns/picks up
- `in_progress → resolved`: Agent or Admin marks resolved
- `in_progress → open`: Reopened (agent needs more info)
- `resolved → closed`: Auto after 7 days, or user/admin confirms
- `resolved → in_progress`: Reopened if user reports issue persists
- `closed → open`: Admin only (exceptional case)

## Routes

```php
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // Status transitions
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'changeStatus'])->name('tickets.status');

    // Comments
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
});
```

## Boundaries

- Always: Validate status transitions, enforce ownership (users see own tickets only), soft-delete tickets
- Ask first: Adding new statuses, changing transition rules
- Never: Allow users to assign tickets, skip validation on status changes

## Success Criteria

- [ ] User can create a ticket with title, description, category, priority
- [ ] Ticket appears in agent queue immediately
- [ ] Agent can change status following the workflow
- [ ] Status transitions are validated (no invalid jumps)
- [ ] Comments display in chronological order
- [ ] Internal comments are hidden from regular users
- [ ] Soft-deleted tickets are recoverable
