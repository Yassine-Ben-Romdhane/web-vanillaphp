# GitHub-Ready Design: Carthage Eagles

**Date:** 2026-03-30
**Scope:** Make the Carthage Eagles PHP project public and GitHub-ready
**Status:** Design Approved

---

## Overview

This is a school assignment project with multiple pages (timeline, team, book collection, store). The goal is to clean up the repository, add documentation, and prepare it for public sharing on GitHub. The store page will eventually integrate with Supabase for database functionality, but that integration is deferred for now.

---

## Deliverables

### 1. `.gitignore`
Create a `.gitignore` file that:
- Excludes system files (`.DS_Store`, `Thumbs.db`)
- Excludes local development artifacts (`.claude/`, `*.bak`, `*.backup`)
- Excludes environment files (`.env`, `.env.local`) for future Supabase credentials
- Excludes node_modules for future npm usage
- Excludes temporary screenshots and backup files
- Prevents accidental commits of sensitive configuration

### 2. `README.md`
Replace the current minimal README with a comprehensive one containing:
- **Project Title & Description** — "Carthage Eagles: A school assignment featuring a timeline, team showcase, book collection, and store"
- **Current Features** — list what's working (pages, responsive design, etc.)
- **Coming Soon** — database integration with Supabase for the store page
- **Tech Stack** — PHP, CSS, JavaScript, (future: Supabase)
- **Setup Instructions** — how to run locally; note that Supabase setup is pending
- **Project Structure** — brief explanation of key files and folders

### 3. Project Structure Organization
No code refactoring. Instead:
- Create `docs/` folder for future architecture notes and Supabase setup guide
- Create `database/` folder and move/organize `schema.sql` there
- Keep `db.php` at root for now (referenced by existing code)
- Keep all PHP pages at root (no breaking changes to existing structure)

### 4. Git History
Clean up and organize recent commits:
- Review current commit history (recent commits are messy: "script fix", "style fixes", "font fixes")
- Create a single squashed/organized commit for the current state if beneficial
- Ensure commit messages are clear for what changes

---

## What's Deferred (For Later)

- **Supabase Integration:** Database setup, API keys, connection logic — happens after assignment submission
- **Code Refactoring:** School project scope doesn't require restructuring PHP files or styles
- **Advanced Documentation:** Detailed architecture docs, contribution guidelines — add when Supabase integration is complete

---

## Success Criteria

✅ `.gitignore` prevents secrets and system files from being tracked
✅ `README.md` clearly explains the project and its current state
✅ Project structure is organized without breaking existing code
✅ Repository is ready for public GitHub hosting
✅ Supabase integration can be added smoothly later without restructuring

---

## Implementation Order

1. Create `.gitignore` with comprehensive patterns
2. Write comprehensive `README.md`
3. Create `docs/` and `database/` folder structure
4. Move `schema.sql` to `database/` folder (update any references if needed)
5. Review and clean up git history if needed
6. Commit all changes with clear message

