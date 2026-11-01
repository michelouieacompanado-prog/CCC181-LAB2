# Implementation Notes — CCC181 Lab 4

This document explains, in plain language, which files were changed for each task, and why.

---

## Task 1 — Form Validation

### New files
| File | Purpose |
|------|---------|
| `app/Http/Requests/StoreProductRequest.php` | Validates the "add product" form before anything reaches the controller |
| `app/Http/Requests/UpdateProductRequest.php` | Same rules for the "edit product" form |

### Modified files
| File | What changed |
|------|-------------|
| `app/Http/Controllers/ProductController.php` | `store()` now type-hints `StoreProductRequest`; `update()` type-hints `UpdateProductRequest`. Laravel automatically runs validation before the method body executes. |
| `resources/views/products/create.blade.php` | Added `@error` directives under each field to display red error messages; `value="{{ old('name') }}"` etc. to keep the user's input after a failed submission. |
| `resources/views/products/edit.blade.php` | Same `@error` and `old()` additions as create. |

### How it works
When you submit the form, Laravel checks every rule in the Form Request. If anything fails, it automatically redirects back with the errors and the original input stored in the session — no extra code needed in the controller.

---

## Task 2 — Image Upload

### New files
| File | Purpose |
|------|---------|
| `database/migrations/2026_11_01_135447_add_image_to_products_table.php` | Adds a nullable `image` text column to the `products` table |
| `resources/views/products/show.blade.php` | A dedicated detail page that shows the full-size image |

### Modified files
| File | What changed |
|------|-------------|
| `app/Models/Product.php` | Added `image` to `$fillable`; added `getImageUrlAttribute()` accessor that builds the full public URL |
| `app/Http/Controllers/ProductController.php` | `store()` uses `$request->file('image')->store('products','public')` to save with a hashed filename. `update()` stores the new file first, then updates the DB, then deletes the old file. `destroy()` deletes the image file from disk. |
| `resources/views/products/create.blade.php` | Added `enctype="multipart/form-data"` and a file input field |
| `resources/views/products/edit.blade.php` | Added `enctype`, a preview of the current image, and a new-file input |
| `resources/views/products/index.blade.php` | Added a thumbnail column; product name is now a clickable link to the show page |

### Key design decisions
- **Hashed filenames**: `->store()` generates a UUID-like name, never using the user's original filename. This prevents path traversal.
- **Store-then-replace order**: On update, the new file is saved first. Only after the DB is updated successfully is the old file deleted. A failed upload therefore never destroys the old image.
- **`php artisan storage:link`**: Run once to create `public/storage → storage/app/public` so images are accessible from the browser.

---

## Task 3 — Role-Based Authorization

### New files
| File | Purpose |
|------|---------|
| `database/migrations/2026_11_01_142637_add_role_to_users_table.php` | Adds a `role` string column (default `'user'`) to the `users` table |
| `app/Policies/ProductPolicy.php` | Defines `viewAny`, `view`, `create`, `update`, `delete` rules. Only admins can mutate products. |
| `database/seeders/AdminUserSeeder.php` | Creates the admin account (`admin@ccc181.test` / `password123`) |

### Modified files
| File | What changed |
|------|-------------|
| `app/Models/User.php` | Added `isAdmin()` and `isUser()` helper methods. **`role` is NOT in `$fillable`** — registration cannot set it. |
| `database/seeders/DatabaseSeeder.php` | Calls `AdminUserSeeder::run()` so seeding creates the admin |
| `routes/web.php` | Replaced `Route::resource` with explicit named routes, each with `can:` middleware |
| `app/Http/Controllers/ProductController.php` | Added `Gate::authorize()` calls at the start of every method (double-layer protection) |
| `resources/views/products/index.blade.php` | Role badge in navbar; `@can` directives hide Add/Edit/Delete buttons from regular users |

### Two-layer check explained
The `can:` middleware on the route rejects unauthorized requests before the controller runs. The `Gate::authorize()` inside the controller catches any route that might be defined without middleware. Both must pass — defence in depth.

---

## Task 4 — Search & Pagination

### Modified files
| File | What changed |
|------|-------------|
| `app/Http/Controllers/ProductController.php` | `index()` reads `$request->query('search')`, filters with `->where('name','LIKE','%…%')` at the database level, paginates 8 per page with `->withQueryString()` |
| `resources/views/products/index.blade.php` | Added a search form (GET), a "Clear" button, a "Showing X–Y of Z" footer, styled pagination links |

### Key detail
`->withQueryString()` ensures the `?search=…` parameter is appended to every pagination link, so clicking "Next page" keeps your search active.

---

## Task 5 — Automated Tests

### New files
| File | Purpose |
|------|---------|
| `tests/Feature/ProductTest.php` | 15 feature tests covering all lab requirements |
| `database/factories/ProductFactory.php` | Faker-based factory for quickly creating Product records in tests |

### Modified files
| File | What changed |
|------|-------------|
| `app/Models/Product.php` | Added `HasFactory` trait so `Product::factory()` works |
| `tests/Feature/Auth/AuthenticationTest.php` | Fixed redirect assertion: `/dashboard` → `products.index` |
| `tests/Feature/Auth/RegistrationTest.php` | Same fix |
| `tests/Feature/ExampleTest.php` | `assertStatus(200)` → `assertRedirect('/login')` since `/` now requires auth |

### Test isolation
`phpunit.xml` is pre-configured with `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`. Every test uses `RefreshDatabase` to start with a clean slate. `Storage::fake('public')` intercepts all file operations so no real files are written to disk during tests.

---

## Documentation Files

| File | Purpose |
|------|---------|
| `TESTING_REPORT.md` | Table of all test cases with expected/actual results and pass/fail status |
| `SECURITY_EXPLANATION.md` | ~1-page explanation of the three security techniques |
| `IMPLEMENTATION_NOTES.md` | This file — plain-language walkthrough of all changes |
