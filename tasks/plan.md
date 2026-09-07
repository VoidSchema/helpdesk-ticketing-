# Implementation Plan: IT Helpdesk & Ticketing System

## Overview

A role-based IT helpdesk ticketing system where end-users submit support requests and agents manage/resolve them. Built on Laravel 13 + Tailwind CSS 4 with a custom Docker dev environment (Nginx, PHP 8.4-FPM, MySQL 8.4).

## Architecture Decisions

- **Laravel 13** — Latest stable, excellent ecosystem, built-in auth scaffolding
- **Tailwind CSS 4** — Utility-first CSS, fast UI development, no custom design system overhead
- **Blade templates** — Server-rendered, no JS framework complexity for a dashboard app
- **MySQL 8.4** — Relational data, foreign keys, full-text search support
- **Custom Docker** — Full control over PHP/Nginx config, no Sail overhead
- **No Breeze/Jetstream** — Roll custom auth for full control over role logic

## Task List

### Phase 1: Foundation (Identity Module)

- [ ] Task 1: Create database schema (ERD) — users, roles, tickets, comments, categories tables
- [ ] Task 2: Implement role system — role enum, User model relationship, middleware
- [ ] Task 3: Build authentication — login, registration, password reset (custom or Breeze)
- [ ] Task 4: Seed demo data — admin, agents, users with sample tickets

### Checkpoint: Foundation
- [ ] Migrations run cleanly
- [ ] Auth flow works (register → login → logout)
- [ ] Roles are enforceable via middleware
- [ ] Demo data seeds correctly

### Phase 2: Core (Tickets Module)

- [ ] Task 5: Ticket CRUD — create, read, update, delete (soft delete)
- [ ] Task 6: Ticket workflow — status transitions (Open → In Progress → Resolved → Closed)
- [ ] Task 7: Ticket assignment — assign/unassign agents, auto-assign option
- [ ] Task 8: Comments/replies — threaded discussion on tickets
- [ ] Task 9: Categories and priority — category management, priority levels

### Checkpoint: Core
- [ ] Full ticket lifecycle works end-to-end
- [ ] Status transitions are enforced
- [ ] Assignment works correctly per role
- [ ] Comments display in order

### Phase 3: Interface (Dashboard Module)

- [ ] Task 10: Agent dashboard — ticket queue, stats cards, recent activity
- [ ] Task 11: User portal — my tickets list, submit new ticket form
- [ ] Task 12: Ticket detail view — full ticket info, comment thread, action buttons
- [ ] Task 13: Search and filtering — by status, priority, category, date range
- [ ] Task 14: Pagination — server-side pagination for all lists

### Checkpoint: Interface
- [ ] Dashboard loads with real data
- [ ] User can submit and track tickets
- [ ] Filtering and search work correctly
- [ ] Responsive on mobile (320px) and desktop (1440px)

### Phase 4: Polish (Notifications Module)

- [ ] Task 15: Email notifications — ticket created, assigned, status changed
- [ ] Task 16: Notification preferences — opt-in/out per event type
- [ ] Task 17: Dashboard notification bell — unread count, recent notifications

### Checkpoint: Complete
- [ ] Emails send on ticket events
- [ ] Notification preferences save correctly
- [ ] All acceptance criteria met
- [ ] Ready for review

## Risks and Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Auth complexity (roles) | High | Start with simple enum-based roles, extend later |
| Email config in Docker | Medium | Use Mailtrap for dev, configure SMTP per environment |
| MySQL performance at scale | Low | Add indexes on hot columns (status, assigned_to, created_at) |
| Blade template complexity | Medium | Keep components small, use partials for repeated UI |

## Decisions (Resolved)

- Use **Laravel Breeze (Blade)** for auth scaffolding
- File attachments **deferred to Phase 5+**
- Agent view is **list-only** (no Kanban board)
