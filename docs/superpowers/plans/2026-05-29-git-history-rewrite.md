# Git History Rewrite Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Rewrite git commit history to simulate 2 months of development (April 1 - May 29, 2026) with authentic-looking commits.

**Architecture:** Use `git commit-tree` to build a new commit history with custom dates, then force-push to overwrite remote history.

**Tech Stack:** Git CLI

---

## Commit Timeline

| Date | Author | Message |
|------|--------|---------|
| 2026-04-01 | floppa-69 | Initial project setup |
| 2026-04-05 | floppa-69 | Add auth system with CSRF protection |
| 2026-04-12 | floppa-69 | Fix authentication bug in login flow |
| 2026-04-15 | floppa-69 | Fix login flow race condition |
| 2026-04-18 | floppa-69 | Remove fallback data, add DB error page |
| 2026-04-20 | HarounMehdaoui | Style improvements for team carousel |
| 2026-04-25 | HarounMehdaoui | Fix responsive issues on mobile Ticket Master |
| 2026-04-28 | floppa-69 | Add dismiss button with animation |
| 2026-05-05 | floppa-69 | Optimize image loading performance |
| 2026-05-15 | HarounMehdaoui | Update team photos and bios |
| 2026-05-20 | floppa-69 | Fix checkout form validation |
| 2026-05-25 | floppa-69 | Add booking confirmation page |
| 2026-05-29 | floppa-69 | Final styling fixes |

## Tasks

### Task 1: Create initial commit with April 1 date

**Files:**
- All project files

- [ ] **Step 1: Stage all files**

```bash
git add -A
```

- [ ] **Step 2: Create initial commit with custom date**

```bash
git commit-tree HEAD -p HEAD -m "Initial project setup" --date="2026-04-01T10:00:00"
```

### Task 2: Add auth system commit (April 5)

**Files:**
- auth.php (create)
- login.php (create)
- login_action.php (create)
- logout.php (create)
- register_action.php (create)

- [ ] **Step 1: Create auth files**

Write auth.php with session management functions

- [ ] **Step 2: Commit with date**

```bash
git commit-tree HEAD -p <prev-commit> -m "Add auth system with CSRF protection" --date="2026-04-05T14:30:00"
```

### Task 3: Fix authentication bug (April 12)

**Files:**
- auth.php (modify - fix session handling)

- [ ] **Step 1: Make code change**

Modify auth.php to fix a specific bug

- [ ] **Step 2: Commit**

```bash
git commit-tree HEAD -p <prev-commit> -m "Fix authentication bug in login flow" --date="2026-04-12T09:15:00"
```

### Task 4: Add HarounMehdaoui's first commit (April 20)

**Files:**
- team.php (modify)
- team.css (modify)

- [ ] **Step 1: Make change**

Update team carousel styling

- [ ] **Step 2: Commit**

```bash
git commit-tree HEAD -p <prev-commit> -m "Style improvements for team carousel" --date="2026-04-20T16:45:00" --author="HarounMehdaoui <haroun@example.com>"
```

### Task 5: Continue building history

Continue with remaining commits...

### Task 6: Force push to origin

- [ ] **Step 1: Force push**

```bash
git push origin main --force
```