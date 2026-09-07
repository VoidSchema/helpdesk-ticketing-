# Spec: Identity Module

## Objective

Implement user authentication and role-based access control for the helpdesk system. Three roles exist: Admin (full access), Agent (manages tickets), User (submits tickets).

## Tech Stack

- Laravel 13 auth (session-based cookies)
- MySQL 8.4 users table with role column
- Blade templates for login/register views
- Tailwind CSS 4 for styling

## Database Schema

### users (extended from default)

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| name | varchar(255) | |
| email | varchar(255) | unique |
| password | varchar(255) | hashed |
| role | enum('admin','agent','user') | default: 'user' |
| is_active | boolean | default: true |
| remember_token | varchar(100) | nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

### roles (enum-based, no separate table)

Roles enforced via:
1. `role` column on users table
2. `App\Enums\Role` PHP enum
3. `role` middleware on routes
4. `@role` Blade directive

## Commands

```
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
php artisan make:middleware RoleMiddleware
```

**Note:** Breeze provides login, registration, password reset, and email verification out of the box. We extend it with role middleware only.

## Role Permissions

| Action | Admin | Agent | User |
|--------|-------|-------|------|
| View all tickets | Yes | Yes (assigned + unassigned) | No (own only) |
| Create tickets | Yes | Yes | Yes |
| Assign tickets | Yes | Yes | No |
| Change ticket status | Yes | Yes | No (close own only) |
| Manage users | Yes | No | No |
| Manage categories | Yes | No | No |
| View dashboard stats | Yes | Yes | No |

## Routes

```php
// Breeze handles: /login, /register, /logout, /forgot-password, /reset-password
// We add role middleware to protect routes

// Protected (auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ticket routes added in tickets module
});

// Admin only
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    // ...
});
```

## Boundaries

- Always: Hash passwords, validate email uniqueness, check role on every route
- Ask first: Adding new roles, changing permission matrix
- Never: Store plain-text passwords, skip auth checks, expose user emails in URLs

## Success Criteria

- [ ] Users can register with name, email, password
- [ ] Users can log in and log out
- [ ] Role is stored and retrievable from User model
- [ ] Middleware blocks unauthorized access
- [ ] Admin can view user list
- [ ] Default role is 'user' on registration
