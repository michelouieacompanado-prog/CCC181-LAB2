<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // Each test runs in a fresh isolated in-memory SQLite database (phpunit.xml)
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    /** Return a user with role = 'admin'. */
    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /** Return a user with role = 'user' (regular). */
    private function regularUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    /** Return a valid product payload (no image). */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Test Product',
            'description' => 'A sample description',
            'price'       => 99.99,
            'qty'         => 10,
        ], $overrides);
    }

    /**
     * Create a fake JPEG UploadedFile without requiring the GD extension.
     * Uses a 1×1 pixel JPEG binary (smallest valid JPEG).
     */
    private function fakeJpeg(string $name = 'product.jpg'): UploadedFile
    {
        // Minimal valid 1×1 JPEG in raw bytes — no GD needed
        $jpegBytes = base64_decode(
            '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8U'
            . 'HRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgN'
            . 'DRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy'
            . 'MjL/wAARCAABAAEDASIAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAA'
            . 'AAAAAAAAAAAAAP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/xAAUEQEAAAAAAAAAAAAAAAAAAAAA'
            . '/9oADAMBAAIRAxEAPwCwABmX/9k='
        );

        $tmpPath = tempnam(sys_get_temp_dir(), 'fake_jpg_');
        file_put_contents($tmpPath, $jpegBytes);

        return new UploadedFile($tmpPath, $name, 'image/jpeg', null, true);
    }

    // ──────────────────────────────────────────────────────────────
    // TASK 1 — VALIDATION
    // ──────────────────────────────────────────────────────────────

    /** TC-01: Submitting an empty form returns validation errors for required fields. */
    public function test_empty_form_submission_returns_validation_errors(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'qty']);
    }

    /** TC-02: A negative price is rejected. */
    public function test_negative_price_is_rejected(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('products.store'), $this->validPayload([
            'price' => -5,
        ]));

        $response->assertSessionHasErrors('price');
    }

    /** TC-03: A non-image file (SVG) is rejected by the image upload rule. */
    public function test_unsupported_file_type_is_rejected(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // SVG file — must be rejected (only jpg,jpeg,png,webp allowed)
        $svg = UploadedFile::fake()->create('logo.svg', 100, 'image/svg+xml');

        $response = $this->actingAs($admin)->post(route('products.store'), array_merge(
            $this->validPayload(),
            ['image' => $svg]
        ));

        $response->assertSessionHasErrors('image');
    }

    // ──────────────────────────────────────────────────────────────
    // TASK 2 — IMAGE UPLOAD
    // ──────────────────────────────────────────────────────────────

    /** TC-04: A valid image is stored in the 'products/' directory and the path is saved to the DB. */
    public function test_valid_image_is_stored_and_path_saved(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $file = $this->fakeJpeg('product.jpg');

        $this->actingAs($admin)->post(route('products.store'), array_merge(
            $this->validPayload(),
            ['image' => $file]
        ));

        $product = Product::first();

        // DB path should start with 'products/'
        $this->assertNotNull($product->image);
        $this->assertStringStartsWith('products/', $product->image);

        // The actual file must exist in the fake public disk
        Storage::disk('public')->assertExists($product->image);
    }

    /** TC-13: Deleting a product removes its image file from storage. */
    public function test_deleting_product_removes_image_from_storage(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $file = $this->fakeJpeg('to-delete.jpg');

        $this->actingAs($admin)->post(route('products.store'), array_merge(
            $this->validPayload(),
            ['image' => $file]
        ));

        $product = Product::first();
        $imagePath = $product->image;

        // Confirm file exists before deletion
        Storage::disk('public')->assertExists($imagePath);

        $this->actingAs($admin)->delete(route('products.destroy', $product->id));

        // File must be gone after product deletion
        Storage::disk('public')->assertMissing($imagePath);
    }

    /** TC-15: A failed image replacement (invalid new file) keeps the old image unchanged. */
    public function test_failed_image_replacement_keeps_old_image(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // Create a product with a valid image first
        $original = $this->fakeJpeg('original.jpg');
        $this->actingAs($admin)->post(route('products.store'), array_merge(
            $this->validPayload(),
            ['image' => $original]
        ));

        $product = Product::first();
        $oldPath = $product->image;

        // Attempt update with an unsupported file type — validation will reject it
        $badFile = UploadedFile::fake()->create('hack.svg', 50, 'image/svg+xml');

        $this->actingAs($admin)->put(route('products.update', $product->id), array_merge(
            $this->validPayload(['name' => 'Updated Name']),
            ['image' => $badFile]
        ));

        // Reload from DB — image path must be unchanged (validation rejected the bad file)
        $product->refresh();
        $this->assertEquals($oldPath, $product->image);
        Storage::disk('public')->assertExists($oldPath);
    }

    // ──────────────────────────────────────────────────────────────
    // TASK 3 — ROLE-BASED AUTHORIZATION
    // ──────────────────────────────────────────────────────────────

    /** TC-05: Regular user gets 403 accessing the create product form (GET). */
    public function test_regular_user_cannot_access_create_form(): void
    {
        $user = $this->regularUser();

        $response = $this->actingAs($user)->get(route('products.create'));

        $response->assertStatus(403);
    }

    /** TC-06: Regular user gets 403 when POSTing a new product. */
    public function test_regular_user_cannot_store_product(): void
    {
        $user = $this->regularUser();

        $response = $this->actingAs($user)->post(route('products.store'), $this->validPayload());

        $response->assertStatus(403);
    }

    /** TC-07: Regular user gets 403 accessing the edit product form (GET). */
    public function test_regular_user_cannot_access_edit_form(): void
    {
        $product = Product::factory()->create();
        $user    = $this->regularUser();

        $response = $this->actingAs($user)->get(route('products.edit', $product->id));

        $response->assertStatus(403);
    }

    /** TC-08: Regular user gets 403 when sending a PUT request to update a product. */
    public function test_regular_user_cannot_update_product(): void
    {
        $product = Product::factory()->create();
        $user    = $this->regularUser();

        $response = $this->actingAs($user)->put(route('products.update', $product->id), $this->validPayload());

        $response->assertStatus(403);
    }

    /** TC-09: Regular user gets 403 when sending a DELETE request. */
    public function test_regular_user_cannot_delete_product(): void
    {
        $product = Product::factory()->create();
        $user    = $this->regularUser();

        $response = $this->actingAs($user)->delete(route('products.destroy', $product->id));

        $response->assertStatus(403);
    }

    /** TC-14: Direct URL access as regular user to protected routes returns 403. */
    public function test_direct_url_access_by_regular_user_returns_403(): void
    {
        $product = Product::factory()->create();
        $user    = $this->regularUser();

        // Check all protected routes return 403 for a regular user
        $this->actingAs($user)->get(route('products.create'))->assertStatus(403);
        $this->actingAs($user)->get(route('products.edit', $product->id))->assertStatus(403);
        $this->actingAs($user)->post(route('products.store'), $this->validPayload())->assertStatus(403);
        $this->actingAs($user)->put(route('products.update', $product->id), $this->validPayload())->assertStatus(403);
        $this->actingAs($user)->delete(route('products.destroy', $product->id))->assertStatus(403);
    }

    /** TC-10: Admin can perform full CRUD operations. */
    public function test_admin_can_perform_full_crud(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        // CREATE
        $this->actingAs($admin)->post(route('products.store'), $this->validPayload([
            'name' => 'Admin Product',
        ]))->assertRedirect(route('products.index'));

        $product = Product::where('name', 'Admin Product')->firstOrFail();
        $this->assertDatabaseHas('products', ['name' => 'Admin Product']);

        // READ (show single)
        $this->actingAs($admin)->get(route('products.show', $product->id))->assertStatus(200);

        // UPDATE
        $this->actingAs($admin)->put(route('products.update', $product->id), $this->validPayload([
            'name' => 'Admin Product Updated',
        ]))->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Admin Product Updated']);

        // DELETE
        $this->actingAs($admin)->delete(route('products.destroy', $product->id))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['name' => 'Admin Product Updated']);
    }

    // ──────────────────────────────────────────────────────────────
    // TASK 4 — SEARCH & PAGINATION
    // ──────────────────────────────────────────────────────────────

    /** TC-11: Searching by name returns matching products. */
    public function test_search_by_name_returns_matching_products(): void
    {
        $admin = $this->admin();

        Product::factory()->create(['name' => 'Blue Sneakers']);
        Product::factory()->create(['name' => 'Red Boots']);

        $response = $this->actingAs($admin)->get(route('products.index', ['search' => 'Blue']));

        $response->assertStatus(200);
        $response->assertSee('Blue Sneakers');
        $response->assertDontSee('Red Boots');
    }

    /** TC-12: Searching for a non-existent name returns zero results without crashing. */
    public function test_search_for_nonexistent_name_returns_no_results(): void
    {
        $admin = $this->admin();

        Product::factory()->create(['name' => 'Available Product']);

        $response = $this->actingAs($admin)->get(route('products.index', ['search' => 'xyzzy99999']));

        $response->assertStatus(200);
        // The "No products found" message should appear
        $response->assertSee('No products found');
    }
}
