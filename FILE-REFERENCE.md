# IT Helpdesk Ticketing System - File Reference

## Project Structure

```
helpdesk-ticketing/
├── docker-compose.yml          # Docker services (Nginx, PHP-FPM, MySQL)
├── .env                        # Root env (MySQL root password)
├── tasks/                      # Planning docs
│   ├── plan.md
│   ├── todo.md
│   └── specs/
├── app/                        # Laravel application
│   ├── .env                    # App config (DB_HOST=mysql, DB_CONNECTION=mysql)
│   ├── routes/web.php          # All routes
│   ├── app/
│   │   ├── Enums/Role.php      # Admin/Agent/User enum
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── TicketController.php      # CRUD, status, assignment
│   │   │   │   ├── CommentController.php     # Comments
│   │   │   │   ├── NotificationController.php # In-app notifications
│   │   │   │   ├── ProfileController.php     # Profile + avatar
│   │   │   │   └── Admin/CategoryController.php
│   │   │   ├── Middleware/RoleMiddleware.php  # Role-based access
│   │   │   └── Requests/
│   │   │       ├── Auth/LoginRequest.php
│   │   │       └── ProfileUpdateRequest.php
│   │   ├── Models/
│   │   │   ├── User.php        # Role casting, avatar, notification prefs
│   │   │   ├── Ticket.php      # SoftDeletes, relationships
│   │   │   ├── Category.php
│   │   │   ├── Comment.php
│   │   │   └── Notification.php # In-app notifications
│   │   └── Notifications/      # Email notifications
│   │       ├── TicketCreated.php
│   │       ├── TicketAssigned.php
│   │       ├── TicketStatusChanged.php
│   │       └── NewComment.php
│   ├── database/
│   │   ├── migrations/
│   │   │   ├── 0001_01_01_000000_create_users_table.php
│   │   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   │   ├── 2026_09_07_000000_add_role_to_users_table.php
│   │   │   ├── 2026_09_07_000001_create_categories_table.php
│   │   │   ├── 2026_09_07_000002_create_tickets_table.php
│   │   │   ├── 2026_09_07_000003_create_comments_table.php
│   │   │   ├── 2026_09_07_000004_create_notifications_table.php
│   │   │   ├── 2026_09_07_000005_remove_closed_at_from_tickets_table.php
│   │   │   ├── 2026_09_07_053148_add_avatar_to_users_table.php
│   │   │   └── 2026_09_07_053517_add_notification_preferences_to_users_table.php
│   │   └── seeders/
│   │       ├── UserSeeder.php
│   │       ├── CategorySeeder.php
│   │       └── TicketSeeder.php
│   └── resources/views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── navigation.blade.php  # Nav with bell icon
│       ├── dashboard.blade.php       # Agent dashboard
│       ├── tickets/
│       │   ├── index.blade.php       # List with filters
│       │   ├── create.blade.php      # New ticket form
│       │   └── show.blade.php        # Detail + comments
│       ├── portal/
│       │   └── my-tickets.blade.php  # User portal
│       ├── notifications/
│       │   └── index.blade.php       # Notifications list
│       ├── admin/
│       │   └── categories/
│       │       └── index.blade.php   # Category management
│       └── profile/
│           ├── edit.blade.php
│           └── partials/
│               ├── update-profile-information-form.blade.php
│               ├── notification-preferences-form.blade.php
│               ├── update-password-form.blade.php
│               └── delete-user-form.blade.php
```

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | | Redirects to login |
| GET | `/dashboard` | `dashboard` | Dashboard with stats |
| GET | `/tickets` | `tickets.index` | List tickets (filtered) |
| GET | `/tickets/create` | `tickets.create` | New ticket form |
| POST | `/tickets` | `tickets.store` | Create ticket |
| GET | `/tickets/{ticket}` | `tickets.show` | Ticket detail |
| PUT | `/tickets/{ticket}` | `tickets.update` | Update ticket |
| DELETE | `/tickets/{ticket}` | `tickets.destroy` | Delete ticket |
| POST | `/tickets/{ticket}/assign` | `tickets.assign` | Assign ticket |
| POST | `/tickets/{ticket}/status` | `tickets.status` | Change status |
| POST | `/tickets/{ticket}/comments` | `comments.store` | Add comment |
| GET | `/my-tickets` | `portal.my-tickets` | User portal |
| GET | `/notifications` | `notifications.index` | Notifications list |
| POST | `/notifications/{id}/read` | `notifications.mark-read` | Mark read |
| POST | `/notifications/read-all` | `notifications.mark-all-read` | Mark all read |
| GET | `/notifications/unread-count` | `notifications.unread-count` | Unread count API |
| GET | `/profile` | `profile.edit` | Edit profile |
| PATCH | `/profile` | `profile.update` | Update profile |
| DELETE | `/profile` | `profile.destroy` | Delete account |
| GET | `/admin/categories` | `admin.categories.index` | Category management |

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Agent | sarah@example.com | password |
| User | john@example.com | password |

## Docker Services

| Service | Container | Port |
|---------|-----------|------|
| Nginx | helpdesk-nginx | 8080 |
| PHP-FPM | helpdesk-php | 9000 |
| MySQL | helpdesk-mysql | 3306 |

## Key Features

1. **Role-based access** (Admin, Agent, User)
2. **Ticket workflow** (Open → In Progress → Resolved)
3. **Comments** with internal notes (agents/admins)
4. **Categories** with management (admin)
5. **Dashboard** with stats and recent activity
6. **User portal** for tracking own tickets
7. **Search & filtering** (status, priority, category)
8. **Email notifications** (ticket created, assigned, status changed, comments)
9. **In-app notifications** with bell icon
10. **Profile management** with avatar upload
11. **Notification preferences** (opt-in/out per event)
