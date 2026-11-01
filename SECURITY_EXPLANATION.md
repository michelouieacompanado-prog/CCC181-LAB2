# Security Explanation — CCC181 Lab 4

## Overview

This document explains how three server-side security measures — **form validation**, **safe file uploads**, and **role-based authorization** — protect the application from common web vulnerabilities. All three controls are enforced on the server; hiding buttons in the UI is a usability nicety only, not a security guarantee.

---

## 1. Server-Side Form Validation

### What it does
Every form submission passes through a **Form Request class** (`StoreProductRequest` / `UpdateProductRequest`). Laravel validates the incoming data against a set of rules _before_ the controller logic runs:

| Field | Rules |
|-------|-------|
| `name` | required, string, max 255 characters |
| `price` | required, numeric, minimum 0 |
| `qty` | required, integer, minimum 0 |
| `description` | nullable, string |
| `image` | nullable, file, image type, mimes: jpg/jpeg/png/webp, max 2 MB |

### Why it matters
- **Prevents malformed data** from reaching the database. Without validation, an attacker could submit a price of `−999999` or an empty name, corrupting records.
- **Stops mass-assignment attacks.** Only the fields returned by `$request->validated()` are passed to `Product::create()` / `$product->update()`, so extra POST fields are silently ignored.
- **Protects against SQL injection indirectly.** While Laravel's query builder already uses PDO prepared statements, validation eliminates unexpected data types that could trigger edge-case bugs.
- **Old values preserved with `old()`** — after a failed validation the user sees their input again, reducing friction while the security check holds firm.

---

## 2. Safe File Uploads

### What it does
- The validation rule `mimes:jpg,jpeg,png,webp` checks the _actual file signature_ (not just the extension), rejecting SVGs, PHP scripts, and other dangerous uploads before they are saved.
- Files are stored with **Laravel's hashed filename** (`$request->file('image')->store('products', 'public')`). The user's original filename is _never_ used, preventing path traversal attacks (e.g. `../../.env`).
- Images are stored under `storage/app/public/products/` and served through the `public/storage` symlink. The web server cannot execute files in this directory.
- On **update**, the new file is stored _first_, the database is updated, and _then_ the old file is deleted. If any step fails, the original image is preserved.
- On **delete**, the image file is removed from disk at the same time the product record is deleted, preventing orphaned files from accumulating.

### Why it matters
- **Remote code execution (RCE)** — If a PHP file were uploaded and the server executed it, an attacker would have full shell access. The mime-type check and the storage path (outside the web root) close this vector.
- **Path traversal** — Using hashed filenames means no user-controlled string ever touches the filesystem path.
- **Denial of service** — The `max:2048` (2 MB) limit prevents large file uploads from exhausting disk space.

---

## 3. Role-Based Authorization (RBAC)

### What it does
The application has two roles: `admin` and `user`. A `ProductPolicy` defines who can perform each action:

| Action | `admin` | `user` |
|--------|---------|--------|
| View list / View single | ✅ | ✅ |
| Create product | ✅ | ❌ |
| Edit / Update product | ✅ | ❌ |
| Delete product | ✅ | ❌ |

Authorization is enforced in **two layers**:
1. **Route middleware** (`can:create,App\Models\Product`) — stops unauthorized requests at the routing level, returning HTTP 403 before the controller even runs.
2. **Controller gate** (`Gate::authorize('create', Product::class)`) — a second check inside each controller method, guarding against middleware bypass.

The `role` column is **not** in `User::$fillable`, so the registration form cannot set it. The admin account is created exclusively through `AdminUserSeeder`, which runs via `php artisan db:seed`.

### Why it matters
- **Privilege escalation** — A regular user cannot craft a POST request to `/products` and create a product, even by bypassing the UI. The server rejects it with 403.
- **Insecure Direct Object Reference (IDOR)** — Accessing `/products/3/edit` directly as a regular user returns 403, not the edit form.
- **Defence in depth** — The double-layer check (route + controller) means a misconfiguration at one layer is caught by the other.
- **Principle of least privilege** — New users always start with the `user` role; elevated access must be granted explicitly through the seeder or directly in the database by an administrator.

---

## Summary

All three controls work together to create a layered defence:

```
Request → Route middleware (RBAC) → Controller (RBAC + Validation) → Storage (Safe filenames + mime check) → Database
```

No security decision relies solely on client-side code. Hiding a button in Blade is for user experience; the actual permission check happens on the server and cannot be bypassed by a determined attacker.
