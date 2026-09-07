# Spec: Notifications Module

## Objective

Notify users and agents of ticket events via email and an in-app notification bell.

## Notification Types

| Event | Recipient | Channel |
|-------|-----------|---------|
| Ticket created | Assigned agent (or all agents if unassigned) | Email + In-app |
| Ticket assigned | Ticket creator | Email + In-app |
| Status changed | Ticket creator + assigned agent | Email + In-app |
| New comment | Ticket participants (creator, assignee, commenters) | Email + In-app |
| Ticket resolved | Ticket creator | Email + In-app |

## Database Schema

### notifications

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| user_id | foreign key | references users.id |
| type | varchar(100) | e.g. 'ticket_created', 'ticket_assigned' |
| title | varchar(255) | |
| message | text | |
| ticket_id | foreign key | nullable, references tickets.id |
| read_at | timestamp | nullable |
| created_at | timestamp | |

## Routes

```php
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});
```

## Implementation

- Use Laravel's built-in `Notification` system with `DatabaseChannel` + `MailChannel`
- Create notification classes: `TicketCreated`, `TicketAssigned`, `TicketStatusChanged`, `NewComment`
- In-app notifications rendered in a dropdown bell icon in the header
- Email templates in `resources/views/emails/`

## Boundaries

- Always: Queue emails (don't block request), respect notification preferences
- Ask first: Adding new notification types, changing email templates
- Never: Send emails synchronously, expose unsubscribe links without auth

## Success Criteria

- [ ] Email sent when ticket is created
- [ ] Email sent when ticket is assigned
- [ ] Email sent when status changes
- [ ] In-app notification appears in bell dropdown
- [ ] Notifications can be marked as read
- [ ] Unread count shows in header badge
