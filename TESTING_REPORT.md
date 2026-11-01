# Testing Report — CCC181 Lab 4

> **Test run:** `php artisan test` · **Date:** 2026-11-01 · **Duration:** 11.75 s  
> **Result:** ✅ **40 passed, 0 failed** (102 assertions)  
> **Database:** SQLite in-memory (isolated from real app database)

---

## Auth Tests (Breeze — fixed to match app redirects)

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| 1 | Login screen renders | HTTP 200 | HTTP 200 | ✅ Pass |
| 2 | Valid credentials authenticate user | Redirect to `/products` | Redirect to `/products` | ✅ Pass |
| 3 | Invalid password is rejected | User remains guest | User remains guest | ✅ Pass |
| 4 | Logout clears session | Redirect to `/`, user is guest | Redirect to `/`, user is guest | ✅ Pass |
| 5 | Registration screen renders | HTTP 200 | HTTP 200 | ✅ Pass |
| 6 | New user can register | Redirect to `/products`, authenticated | Redirect to `/products`, authenticated | ✅ Pass |

---

## Task 1 — Form Validation

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| TC-01 | Empty form submission | Session has errors for `name`, `price`, `qty` | Session has errors for `name`, `price`, `qty` | ✅ Pass |
| TC-02 | Negative price (`-5`) | Session has error for `price` | Session has error for `price` | ✅ Pass |
| TC-03 | SVG file uploaded | Session has error for `image` | Session has error for `image` | ✅ Pass |

---

## Task 2 — Image Upload

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| TC-04 | Valid JPEG uploaded | `image` path starts with `products/`; file exists on disk | Path `products/…`; file exists in fake storage | ✅ Pass |
| TC-13 | Product deleted | Image file removed from storage | `Storage::assertMissing` passed | ✅ Pass |
| TC-15 | Replacement with invalid file | Old image path unchanged in DB; old file still on disk | `$product->image` unchanged; old file exists | ✅ Pass |

---

## Task 3 — Role-Based Authorization

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| TC-05 | Regular user: GET `/products/create` | HTTP 403 | HTTP 403 | ✅ Pass |
| TC-06 | Regular user: POST `/products` | HTTP 403 | HTTP 403 | ✅ Pass |
| TC-07 | Regular user: GET `/products/{id}/edit` | HTTP 403 | HTTP 403 | ✅ Pass |
| TC-08 | Regular user: PUT `/products/{id}` | HTTP 403 | HTTP 403 | ✅ Pass |
| TC-09 | Regular user: DELETE `/products/{id}` | HTTP 403 | HTTP 403 | ✅ Pass |
| TC-14 | Direct URL access (all protected routes) | HTTP 403 for all | HTTP 403 for all 5 routes | ✅ Pass |
| TC-10 | Admin: full CRUD cycle | Create → show → update → delete all succeed | All 4 operations succeeded | ✅ Pass |

---

## Task 4 — Search & Pagination

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| TC-11 | Search "Blue" with matching product | Shows "Blue Sneakers", hides "Red Boots" | "Blue Sneakers" visible, "Red Boots" not visible | ✅ Pass |
| TC-12 | Search for "xyzzy99999" (no match) | Page loads (200), shows "No products found" | HTTP 200, "No products found" text present | ✅ Pass |

---

## Breeze Profile Tests (unchanged from Breeze scaffold)

| # | Test Case | Expected Result | Actual Result | Pass/Fail |
|---|-----------|-----------------|---------------|-----------|
| P-1 | Profile page renders | HTTP 200 | HTTP 200 | ✅ Pass |
| P-2 | Profile information updated | Data saved | Data saved | ✅ Pass |
| P-3 | Email unchanged: verified status kept | Status unchanged | Status unchanged | ✅ Pass |
| P-4 | Account deletion | Account deleted | Account deleted | ✅ Pass |
| P-5 | Wrong password: account not deleted | Rejected | Rejected | ✅ Pass |

---

## Summary

| Category | Tests | Passed | Failed |
|----------|-------|--------|--------|
| Auth (Breeze) | 6 | 6 | 0 |
| Validation (Task 1) | 3 | 3 | 0 |
| Image Upload (Task 2) | 3 | 3 | 0 |
| Authorization (Task 3) | 7 | 7 | 0 |
| Search & Pagination (Task 4) | 2 | 2 | 0 |
| Profile (Breeze) | 5 | 5 | 0 |
| Unit (Breeze) | 1 | 1 | 0 |
| **Total** | **40** | **40** | **0** |

---

> **Note on GD extension:** `UploadedFile::fake()->image()` requires the PHP GD extension, which is not enabled on this machine's CLI PHP. Image upload tests use a handcrafted 1×1 pixel JPEG binary instead — the same validation logic is exercised.
