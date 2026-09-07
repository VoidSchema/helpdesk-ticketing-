# ADR-001: Custom Docker Development Environment

## Status
Accepted

## Date
2026-09-07

## Context
We need a local development environment for a Laravel application. Options include:
- Laravel Sail (official Docker setup)
- Herd (native macOS app)
- Custom Docker Compose

Key requirements:
- PHP 8.4 with common extensions (pdo_mysql, gd, zip, intl, redis)
- Nginx as reverse proxy (not Apache)
- MySQL 8.4 with persistent storage
- Health checks for service dependency management
- Minimal overhead, fast rebuilds

## Decision
Use a custom Docker Compose setup with three services: Nginx, PHP-FPM, and MySQL.

## Alternatives Considered

### Laravel Sail
- Pros: Official, well-documented, includes MySQL/PostgreSQL
- Cons: Uses Apache by default, heavier resource usage, less control over PHP config
- Rejected: We want Nginx + PHP-FPM for production parity and lighter footprint

### Herd
- Pros: Native performance, zero config
- Cons: macOS only, no Linux/Windows support, harder to share team config
- Rejected: Team may not all use macOS; Docker is portable

### Laravel Valet
- Pros: Lightweight, native PHP
- Cons: macOS only, requires Homebrew, harder to manage MySQL version
- Rejected: Same portability concern as Herd

## Consequences

### Positive
- Full control over PHP extensions and config
- Nginx + PHP-FPM matches production patterns
- Health checks ensure services start in correct order
- Persistent MySQL volume prevents data loss on rebuild
- Portable across team members' OS

### Negative
- Must maintain Dockerfile and configs manually
- No built-in Xdebug setup (must add separately if needed)
- Team members need Docker installed

### Neutral
- Docker Compose config is version-controlled
- PHP-FPM healthcheck script is a custom addition
- Nginx config is minimal and production-like
