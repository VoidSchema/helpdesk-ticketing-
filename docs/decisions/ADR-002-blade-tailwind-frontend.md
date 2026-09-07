# ADR-002: Laravel Blade + Tailwind CSS for Frontend

## Status
Accepted

## Date
2026-09-07

## Context
We need to build a responsive UI for a helpdesk ticketing system. The UI consists of:
- Dashboard with stats cards and ticket tables
- Forms for ticket creation and editing
- Ticket detail view with comment threads
- Auth pages (login, register)

Key requirements:
- Server-rendered (no SPA complexity)
- Fast development velocity
- Professional appearance (not "AI-generated" look)
- Accessible (WCAG 2.1 AA)
- Responsive (mobile to desktop)

## Decision
Use Laravel Blade templates with Tailwind CSS 4 (via Vite).

## Alternatives Considered

### Laravel Livewire + Alpine.js
- Pros: Dynamic UI without full SPA, real-time updates
- Cons: More complexity for a primarily form-based app, learning curve
- Rejected: Blade is simpler for our use case; can add Livewire later if needed

### Inertia.js + Vue/React
- Pros: SPA-like UX, type-safe with TypeScript
- Cons: Heavier setup, requires JS build pipeline, more moving parts
- Rejected: Overkill for a server-rendered dashboard; Blade is sufficient

### Bootstrap 5
- Pros: Widely known, good component library
- Cons: Heavier CSS, less customizable, "Bootstrap look"
- Rejected: Tailwind is more flexible and produces smaller CSS bundles

### Filament (admin panel)
- Pros: Rapid CRUD generation, built-in tables/forms
- Cons: Opinionated, harder to customize, may not fit our exact workflow
- Rejected: We want full control over the UI for a custom helpdesk experience

## Consequences

### Positive
- Blade is simple and well-documented
- Tailwind CSS 4 has excellent build performance via Vite
- Utility-first CSS avoids bloat and custom CSS accumulation
- No JavaScript framework complexity for server-rendered pages
- Easy to add Alpine.js for minor interactivity later

### Negative
- Blade lacks reactivity (full page reloads for interactions)
- No component reuse across frontend/backend (compared to Inertia)
- Must write HTML templates manually (no component library like Filament)

### Neutral
- Vite handles CSS/JS bundling efficiently
- Tailwind purge removes unused CSS in production
- Blade components provide some reuse within templates
