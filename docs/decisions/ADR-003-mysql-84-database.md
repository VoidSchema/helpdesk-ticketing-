# ADR-003: MySQL 8.4 as Primary Database

## Status
Accepted

## Date
2026-09-07

## Context
We need a relational database for the helpdesk system. Options:
- MySQL 8.4
- PostgreSQL 16
- MariaDB 11

Key requirements:
- ACID transactions for ticket state changes
- Foreign key support for relational integrity
- Full-text search for ticket content
- JSON support for flexible fields (if needed)
- Widely supported by Laravel

## Decision
Use MySQL 8.4.

## Alternatives Considered

### PostgreSQL 16
- Pros: Better JSON support, full-text search, extensions ecosystem
- Cons: Team familiarity varies, slightly more config for Laravel
- Rejected: MySQL is simpler for our needs; PostgreSQL advantages don't apply

### MariaDB 11
- Pros: MySQL-compatible, open-source
- Cons: Slightly behind on features, less documentation
- Rejected: MySQL 8.4 is more mature and widely documented

## Consequences

### Positive
- Laravel has excellent MySQL support
- MySQL 8.4 supports window functions, CTEs, and JSON columns
- Persistent Docker volume for data safety
- Health checks ensure MySQL is ready before app starts

### Negative
- MySQL full-text search is less powerful than PostgreSQL's
- No native array types (but we don't need them)

### Neutral
- MySQL 8.4 is well-supported by hosting providers
- Easy to migrate to PostgreSQL later if needed
