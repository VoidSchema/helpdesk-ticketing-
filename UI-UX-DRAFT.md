# UI/UX Design Draft: IT Helpdesk & Ticketing System

## Design System

### Color Palette

| Token | Value | Usage |
|-------|-------|-------|
| `bg-primary` | `#1e293b` (slate-800) | Sidebar, header |
| `bg-surface` | `#ffffff` | Cards, content areas |
| `bg-muted` | `#f1f5f9` (slate-100) | Table rows, alternating bg |
| `text-primary` | `#0f172a` (slate-900) | Headings, body text |
| `text-secondary` | `#64748b` (slate-500) | Helper text, timestamps |
| `border-default` | `#e2e8f0` (slate-200) | Card borders, dividers |

### Status Colors (semantic, never color-only)

| Status | Badge | Text |
|--------|-------|------|
| Open | `bg-blue-100 text-blue-800` | Open |
| In Progress | `bg-yellow-100 text-yellow-800` | In Progress |
| Resolved | `bg-green-100 text-green-800` | Resolved |
| Closed | `bg-gray-100 text-gray-600` | Closed |

### Priority Colors

| Priority | Badge |
|----------|-------|
| Low | `bg-slate-100 text-slate-600` |
| Medium | `bg-blue-100 text-blue-700` |
| High | `bg-orange-100 text-orange-700` |
| Urgent | `bg-red-100 text-red-700` |

### Typography

- **Headings**: Inter or system font stack, font-semibold
- **Body**: Inter or system font stack, font-normal
- **Monospace**: JetBrains Mono (for ticket IDs)

### Spacing

- Base unit: 4px (Tailwind `p-1` = 4px)
- Card padding: `p-6` (24px)
- Section gap: `gap-6` (24px)
- Inline gap: `gap-2` (8px) or `gap-3` (12px)

## Layout

### App Shell

```
┌─────────────────────────────────────────────────┐
│  Header: Logo  │  Search  │  🔔  │  Avatar ▼  │
├────────┬────────────────────────────────────────┤
│        │                                        │
│  Side  │           Content Area                 │
│  bar   │                                        │
│        │                                        │
│  Nav   │                                        │
│  items │                                        │
│        │                                        │
└────────┴────────────────────────────────────────┘
```

- **Sidebar**: 240px fixed, collapsible to 64px icon-only on mobile
- **Header**: 64px height, sticky top
- **Content**: Fluid, max-width 1280px, centered

### Agent Dashboard

```
┌─────────────────────────────────────────────┐
│  Stats Cards (4 across on desktop, 2 on mobile) │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐          │
│  │Total│ │Open │ │Prog │ │Done │          │
│  └─────┘ └─────┘ └─────┘ └─────┘          │
├─────────────────────────────────────────────┤
│  Quick Filters: [All] [My] [Unassigned] [!] │
├─────────────────────────────────────────────┤
│  Ticket Table                               │
│  ┌────┬──────────┬──────┬──────┬───────┐   │
│  │ ID │ Title    │ Stat │ Pri  │ Agent │   │
│  ├────┼──────────┼──────┼──────┼───────┤   │
│  │... │ ...      │ ...  │ ...  │ ...   │   │
│  └────┴──────────┴──────┴──────┴───────┘   │
│  [Pagination: < 1 2 3 ... 10 >]             │
└─────────────────────────────────────────────┘
```

### User Portal - New Ticket Form

```
┌─────────────────────────────────────────────┐
│  Submit a New Ticket                        │
├─────────────────────────────────────────────┤
│  Title: [________________________]          │
│                                             │
│  Category: [Dropdown ▼]                     │
│                                             │
│  Priority: ( ) Low (•) Medium ( ) High     │
│                                             │
│  Description:                               │
│  ┌─────────────────────────────────────┐    │
│  │                                     │    │
│  │                                     │    │
│  └─────────────────────────────────────┘    │
│                                             │
│  [Cancel]  [Submit Ticket]                  │
└─────────────────────────────────────────────┘
```

### Ticket Detail

```
┌─────────────────────────────────────────────┐
│  #TK-1042  Login page returns 500 error     │
│  ┌────────┐ ┌──────────┐ ┌────────────┐    │
│  │ Open   │ │ High     │ │ IT Support │    │
│  └────────┘ └──────────┘ └────────────┘    │
├─────────────────────────────────────────────┤
│  ┌──────────────────┐  ┌──────────────────┐ │
│  │ Details          │  │ Activity         │ │
│  │ Created: Sep 1   │  │                  │ │
│  │ By: John Doe     │  │ Sep 1 - Created  │ │
│  │ Assigned: Jane   │  │ Sep 2 - Comment  │ │
│  │                  │  │ Sep 3 - Status → │ │
│  │ [Change Status]  │  │   In Progress    │ │
│  │ [Reassign]       │  │                  │ │
│  └──────────────────┘  └──────────────────┘ │
├─────────────────────────────────────────────┤
│  Add Comment:                               │
│  ┌─────────────────────────────────────┐    │
│  │                                     │    │
│  └─────────────────────────────────────┘    │
│  [ ] Internal note (agents only)            │
│  [Post Comment]                             │
└─────────────────────────────────────────────┘
```

## Responsive Breakpoints

| Breakpoint | Sidebar | Stats Cards | Table |
|------------|---------|-------------|-------|
| 320px (mobile) | Hidden, hamburger toggle | 2 columns | Stack rows |
| 768px (tablet) | Collapsed icon-only | 2 columns | Full table |
| 1024px (desktop) | Full 240px | 4 columns | Full table |
| 1440px (wide) | Full 240px | 4 columns | Full table |

## Component Inventory

| Component | Blade File | Reusable? |
|-----------|-----------|-----------|
| Stats Card | `components/stats-card.blade.php` | Yes |
| Status Badge | `components/status-badge.blade.php` | Yes |
| Priority Badge | `components/priority-badge.blade.php` | Yes |
| Ticket Table Row | `components/ticket-row.blade.php` | Yes |
| Comment Card | `components/comment-card.blade.php` | Yes |
| Pagination | Laravel's built-in | Yes |
| Alert/Flash | `components/alert.blade.php` | Yes |
| Form Input | `components/form/input.blade.php` | Yes |
| Form Select | `components/form/select.blade.php` | Yes |
| Form Textarea | `components/form/textarea.blade.php` | Yes |

## Accessibility Checklist

- All forms have visible labels (not placeholder-only)
- Status badges include text + color (not color-only)
- Skip-to-content link for keyboard users
- Focus visible on all interactive elements
- ARIA labels on icon-only buttons
- Error messages associated with form fields via `aria-describedby`
