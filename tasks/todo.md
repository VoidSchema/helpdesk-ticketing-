# Task Breakdown: IT Helpdesk & Ticketing System

## Phase 1: Foundation (Identity Module)

- [x] **Task 1: Create database schema**
  - Description: Create all migration files for users (with role), tickets, categories, comments, and notifications tables.
  - Acceptance: All migrations run cleanly with `php artisan migrate`. Tables have correct columns, types, foreign keys, and indexes.
  - Verify: `docker compose exec php-fpm php artisan migrate --force` succeeds. `docker compose exec php-fpm php artisan migrate:status` shows all migrations completed.
  - Files: `database/migrations/*_create_*.php`
  - Scope: M (3-5 files)

- [x] **Task 2: Implement role system**
  - Description: Create Role enum, User model relationship, and role middleware for access control.
  - Acceptance: `App\Enums\Role` enum exists with admin/agent/user values. `User` model has `role` attribute. `RoleMiddleware` blocks unauthorized routes.
  - Verify: `docker compose exec php-fpm php artisan tinker` — `$user = new App\Models\User(['role' => 'admin']); echo $user->role;` outputs "admin". Middleware blocks access when role doesn't match.
  - Files: `app/Enums/Role.php`, `app/Models/User.php`, `app/Http/Middleware/RoleMiddleware.php`, `bootstrap/app.php`
  - Scope: S (1-2 files)

- [x] **Task 3: Install and customize Laravel Breeze**
  - Description: Install Laravel Breeze with Blade stack. Customize views to match our design system. Add role middleware.
  - Acceptance: Breeze auth pages work (login, register, logout). Views styled with Tailwind. Role middleware blocks unauthorized routes.
  - Verify: Register a new user, log in, see dashboard, log out. Check `users` table has role column.
  - Files: `resources/views/auth/*.blade.php`, `resources/views/layouts/*.blade.php`, `app/Http/Middleware/RoleMiddleware.php`, `bootstrap/app.php`
  - Scope: S (1-2 files — Breeze scaffolds most of it)

- [x] **Task 4: Seed demo data**
  - Description: Create seeders for admin user, agent users, regular users, categories, and sample tickets.
  - Acceptance: `php artisan db:seed` creates 1 admin, 2 agents, 5 users, 8 categories, and 20 sample tickets with varied statuses.
  - Verify: `docker compose exec php-fpm php artisan db:seed` completes. Login as admin@example.com (password: password) and see seeded tickets.
  - Files: `database/seeders/DatabaseSeeder.php`, `database/seeders/UserSeeder.php`, `database/seeders/CategorySeeder.php`, `database/seeders/TicketSeeder.php`
  - Scope: M (3-5 files)

### Checkpoint: Foundation
- [x] All migrations run cleanly
- [x] Auth flow works (register → login → logout)
- [x] Roles are enforceable via middleware
- [x] Demo data seeds correctly
- [x] Review with human before proceeding

## Phase 2: Core (Tickets Module)

- [x] **Task 5: Ticket CRUD**
  - Description: Create Ticket model, controller, and views for creating, viewing, editing, and soft-deleting tickets.
  - Acceptance: User can create a ticket (title, description, category, priority). User can view their tickets. Agent/Admin can view all tickets. Soft delete works.
  - Verify: Create a ticket via form. See it in the ticket list. View ticket detail. Soft delete a ticket (it disappears from list but exists in DB).
  - Files: `app/Models/Ticket.php`, `app/Http/Controllers/TicketController.php`, `resources/views/tickets/index.blade.php`, `resources/views/tickets/create.blade.php`, `resources/views/tickets/show.blade.php`, `routes/web.php`
  - Scope: L (5-8 files) — split into sub-tasks if needed

- [x] **Task 6: Ticket workflow**
  - Description: Implement status transitions (open → in_progress → resolved → closed) with validation rules.
  - Acceptance: Status can only change following the defined workflow. Invalid transitions are rejected. Status changes are logged.
  - Verify: Try to change status from open to resolved directly — should fail. Change open → in_progress → resolved — should work.
  - Files: `app/Models/Ticket.php`, `app/Http/Controllers/TicketController.php` (status method), `app/Enums/TicketStatus.php`
  - Scope: S (1-2 files)

- [x] **Task 7: Ticket assignment**
  - Description: Allow agents/admins to assign tickets to themselves or other agents.
  - Acceptance: Agent can assign a ticket to themselves. Admin can assign any ticket to any agent. Unassigned tickets appear in queue.
  - Verify: Assign a ticket to an agent. Check `assigned_to` is set. Unassign and verify it's null. Check unassigned filter shows unassigned tickets.
  - Files: `app/Http/Controllers/TicketController.php` (assign method), `resources/views/tickets/show.blade.php`
  - Scope: S (1-2 files)

- [x] **Task 8: Comments/replies**
  - Description: Add threaded comments to tickets with internal note support.
  - Acceptance: User can add a comment. Agent can add internal notes (hidden from regular users). Comments display in chronological order.
  - Verify: Add a comment to a ticket. See it in the thread. Add an internal note as agent. Log in as regular user — note should be hidden.
  - Files: `app/Models/Comment.php`, `app/Http/Controllers/CommentController.php`, `resources/views/components/comment-card.blade.php`, `resources/views/tickets/show.blade.php`
  - Scope: M (3-5 files)

- [x] **Task 9: Categories and priority**
  - Description: Create category management and priority levels for tickets.
  - Acceptance: Admin can create/edit/delete categories. Users select category when creating ticket. Priority levels (low/medium/high/urgent) are available.
  - Verify: Create a category via admin panel. Create a ticket with that category. See category in ticket detail. Change priority — verify it updates.
  - Files: `app/Models/Category.php`, `app/Http/Controllers/CategoryController.php`, `resources/views/admin/categories/index.blade.php`, `database/seeders/CategorySeeder.php`
  - Scope: M (3-5 files)

### Checkpoint: Core
- [x] Full ticket lifecycle works end-to-end
- [x] Status transitions are enforced
- [x] Assignment works correctly per role
- [x] Comments display in order
- [x] Review with human before proceeding

## Phase 3: Interface (Dashboard Module)

- [x] **Task 10: Agent dashboard**
  - Description: Build the agent/admin dashboard with stats cards, ticket queue, and quick filters.
  - Acceptance: Dashboard shows total/open/in-progress/resolved counts. Ticket table displays with sortable columns. Quick filters work (all, my tickets, unassigned, high priority).
  - Verify: Load dashboard — see real counts. Click "My tickets" — see only assigned tickets. Click "Unassigned" — see unassigned tickets.
  - Files: `app/Http/Controllers/DashboardController.php`, `resources/views/dashboard/index.blade.php`, `resources/views/components/stats-card.blade.php`, `resources/views/components/ticket-table.blade.php`
  - Scope: L (5-8 files)

- [x] **Task 11: User portal**
  - Description: Build the user-facing portal for submitting and tracking tickets.
  - Acceptance: User sees "My Tickets" list. User can submit new ticket. User can view their ticket detail.
  - Verify: Log in as regular user. See only own tickets. Submit a new ticket. View ticket detail.
  - Files: `resources/views/portal/index.blade.php`, `resources/views/portal/my-tickets.blade.php`, `app/Http/Controllers/PortalController.php`
  - Scope: M (3-5 files)

- [x] **Task 12: Ticket detail view**
  - Description: Build the full ticket detail page with info sidebar, comment thread, and action buttons.
  - Acceptance: Ticket detail shows all info. Comment thread is chronological. Action buttons work (change status, assign, comment).
  - Verify: Open a ticket — see all details. Add a comment — appears in thread. Change status — badge updates. Assign ticket — agent name updates.
  - Files: `resources/views/tickets/show.blade.php`, `resources/views/components/comment-card.blade.php`, `resources/views/components/status-badge.blade.php`
  - Scope: M (3-5 files)

- [x] **Task 13: Search and filtering**
  - Description: Add search by title/description and filtering by status, priority, category, date range.
  - Acceptance: Search box filters tickets by text. Filter dropdowns work independently and combine. Filters persist in URL (shareable).
  - Verify: Search for a keyword — see matching tickets. Filter by status "open" — see only open tickets. Combine search + filter — see intersection.
  - Files: `app/Http/Controllers/TicketController.php` (index method), `resources/views/tickets/index.blade.php`
  - Scope: M (3-5 files)

- [x] **Task 14: Pagination**
  - Description: Add server-side pagination to all ticket lists.
  - Acceptance: Lists show 15 items per page. Pagination controls work. Page state persists in URL.
  - Verify: See pagination controls on ticket list. Click page 2 — new results. Change filter — pagination resets to page 1.
  - Files: `resources/views/components/pagination.blade.php`, `app/Http/Controllers/TicketController.php`
  - Scope: S (1-2 files)

### Checkpoint: Interface
- [x] Dashboard loads with real data
- [x] User can submit and track tickets
- [x] Filtering and search work correctly
- [x] Responsive on mobile (320px) and desktop (1440px)
- [x] Review with human before proceeding

## Phase 4: Polish (Notifications Module)

- [x] **Task 15: Email notifications**
  - Description: Implement email notifications for ticket created, assigned, status changed, and new comment events.
  - Acceptance: Email sent when ticket is created. Email sent when ticket is assigned. Email sent when status changes. Emails contain relevant ticket info.
  - Verify: Create a ticket — check inbox (or Mailtrap). Assign ticket — check assignee inbox. Change status — check creator inbox.
  - Files: `app/Notifications/TicketCreated.php`, `app/Notifications/TicketAssigned.php`, `app/Notifications/TicketStatusChanged.php`, `resources/views/emails/*.blade.php`
  - Scope: M (3-5 files)

- [x] **Task 16: Notification preferences**
  - Description: Allow users to opt-in/out of email notifications per event type.
  - Acceptance: User can update notification preferences. Preferences are respected when sending emails.
  - Verify: Disable "ticket assigned" notification. Assign a ticket — no email sent. Re-enable — email sent on next assignment.
  - Files: `app/Models/User.php` (notification preferences), `app/Http/Controllers/NotificationPreferenceController.php`, `resources/views/profile/notifications.blade.php`
  - Scope: S (1-2 files)

- [x] **Task 17: Dashboard notification bell**
  - Description: Add in-app notification bell icon in header with unread count and dropdown.
  - Acceptance: Bell icon shows unread count badge. Clicking bell shows recent notifications. Notifications can be marked as read.
  - Verify: New ticket event — bell count increases. Click bell — see notification list. Click notification — marked as read, count decreases.
  - Files: `resources/views/components/notification-bell.blade.php`, `app/Http/Controllers/NotificationController.php`, `resources/views/notifications/index.blade.php`, `resources/views/layouts/app.blade.php`
  - Scope: M (3-5 files)

### Checkpoint: Complete
- [ ] Emails send on ticket events
- [ ] Notification preferences save correctly
- [ ] All acceptance criteria met
- [ ] Ready for review
