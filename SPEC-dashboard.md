# Spec: Dashboard Module

## Objective

Provide role-appropriate views: Admin/Agent see a ticket queue with stats; Users see a portal to submit and track their own tickets.

## Views

### Agent/Admin Dashboard (`/dashboard`)

- **Stats cards**: Total tickets, Open, In Progress, Resolved (today/week/month)
- **Ticket queue**: Sortable table with columns: ID, Title, Status, Priority, Assigned To, Created, Updated
- **Quick filters**: My tickets, Unassigned, High priority, Overdue
- **Recent activity**: Last 10 ticket updates with timestamps

**Note:** List view only — no Kanban board. Users can sort and filter the table.

### User Portal (`/my-tickets`)

- **My tickets list**: User's submitted tickets with status badges
- **Submit new ticket**: Form with title, description, category, priority
- **Ticket detail**: Full ticket view with comment thread

### Ticket Detail (`/tickets/{id}`)

- **Header**: Ticket ID, title, status badge, priority badge
- **Info sidebar**: Category, Created by, Assigned to, Dates
- **Comment thread**: Chronological list with internal note indicators
- **Action buttons**: Change status, Assign (agents/admins), Add comment
- **Attachments area**: (Phase 5+ placeholder)

## Components

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Main layout (nav, sidebar, content)
│   └── guest.blade.php        # Auth pages layout
├── components/
│   ├── stats-card.blade.php   # Reusable stat card
│   ├── ticket-table.blade.php # Ticket list table
│   ├── status-badge.blade.php # Colored status indicator
│   ├── priority-badge.blade.php
│   └── comment-card.blade.php # Single comment display
├── dashboard/
│   └── index.blade.php        # Agent/Admin dashboard
├── tickets/
│   ├── index.blade.php        # Ticket list (all/filtered)
│   ├── create.blade.php       # New ticket form
│   └── show.blade.php         # Ticket detail
└── auth/
    ├── login.blade.php
    └── register.blade.php
```

## Design Principles

- **Mobile-first**: Responsive grid, hamburger nav on small screens
- **No AI aesthetic**: Clean, professional, no purple gradients or oversized cards
- **Semantic colors**: Status = green (resolved), yellow (in progress), red (urgent), blue (open)
- **Consistent spacing**: 4px base unit, Tailwind scale (p-1, p-2, p-4, etc.)
- **Accessible**: Keyboard nav, ARIA labels, focus management, color + text for status

## Success Criteria

- [ ] Dashboard loads in < 2s with 1000 tickets
- [ ] Stats update in real-time (or refresh on page load)
- [ ] Ticket table is sortable and filterable
- [ ] Form validation is inline and clear
- [ ] Works on 320px mobile and 1440px desktop
- [ ] All interactive elements are keyboard accessible
