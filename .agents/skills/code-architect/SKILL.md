---
name: code-architect
description: >-
  Expert Software Architecture, Security Auditing (OWASP, SQLi, XSS), High-Performance System Design & Clean Code.
  Use when designing APIs, refactoring legacy components, optimizing database queries, or structuring large projects.
---

# Code Architect: Enterprise Software Design & Security

## Architecture Principles
1. **SOLID & DRY**: Single Responsibility, Open-Closed, Liskov Substitution, Interface Segregation, Dependency Inversion.
2. **Security by Default**:
   - Parameterized queries / ORM protection against SQL Injection.
   - Strict output escaping (`htmlspecialchars`, JSX encoding) against XSS.
   - Robust CSRF tokens, secure cookie flags (`HttpOnly`, `SameSite=Lax/Strict`).
3. **High-Performance & Scalability**:
   - Index database foreign keys and frequently filtered columns.
   - Cache expensive computation or query results (Redis/Memcached/APCu).
   - Async non-blocking execution for I/O-bound tasks.
