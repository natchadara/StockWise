<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_visitors_can_view_items_and_categories_but_not_private_pages(): void
    {
        $this->get('/auth/login')->assertOk()->assertSee('Staff login');
        $this->get('/items')->assertOk()->assertSee('USB Keyboard')->assertDontSee('North IT Supply');
        $this->get('/categories')->assertOk()->assertSee('Electronics');
        $this->get('/items/1')
            ->assertOk()
            ->assertSee('USB Keyboard')
            ->assertDontSee('North IT Supply')
            ->assertDontSee('Reorder level');
        $this->get('/categories/1')->assertOk()->assertSee('Electronics');

        $this->get('/suppliers')->assertRedirect(route('login'));
        $this->get('/reports/low-stock')->assertRedirect(route('login'));
        $this->get('/categories/create')->assertRedirect(route('login'));
    }

    public function test_admin_can_create_update_and_delete_categories_and_suppliers(): void
    {
        $this->actingAs($this->makeUser('ADMIN'));
        $this->get('/categories/create')->assertOk();

        $response = $this->post('/categories', [
            'code' => 'CT900',
            'name' => 'Test category',
            'description' => 'Created during the feature test',
        ]);
        $response->assertRedirect()->assertSessionHas('status', 'Category CT900 was created.');

        $category = Category::where('code', 'CT900')->firstOrFail();
        $this->get("/categories/{$category->id}/update")->assertOk();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Test category']);

        $this->post("/categories/{$category->id}", [
            'code' => 'CT900',
            'name' => 'Updated category',
            'description' => '',
        ])->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated category']);

        $this->post("/categories/{$category->id}/delete")->assertRedirect(route('categories.list'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        $this->get('/suppliers/create')->assertOk();
        $this->post('/suppliers', [
            'code' => 'SP900',
            'name' => 'Test supplier',
            'phone' => '555-0900',
            'email' => 'supplier@example.test',
        ])->assertRedirect();

        $supplier = Supplier::where('code', 'SP900')->firstOrFail();
        $this->get("/suppliers/{$supplier->id}/update")->assertOk();
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'Test supplier']);

        $this->post("/suppliers/{$supplier->id}", [
            'code' => 'SP900',
            'name' => 'Updated supplier',
            'phone' => '555-0901',
            'email' => 'updated@example.test',
        ])->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'Updated supplier']);

        $this->post("/suppliers/{$supplier->id}/delete")->assertRedirect(route('suppliers.list'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_staff_can_update_item_stock_but_cannot_manage_admin_only_records(): void
    {
        $staff = $this->makeUser('STAFF');
        $item = $this->makeItem();

        $this->actingAs($staff)
            ->get("/items/{$item->id}/update")
            ->assertOk();

        $this->actingAs($staff)
            ->post("/items/{$item->id}", [
                'sku' => $item->sku,
                'name' => $item->name,
                'category_id' => $item->category_id,
                'supplier_id' => $item->supplier_id,
                'quantity' => 3,
                'reorder_level' => $item->reorder_level,
                'price' => $item->price,
            ])
            ->assertRedirect(route('items.view', $item));

        $this->get('/reports/low-stock')->assertOk();
        $this->assertDatabaseHas('items', ['id' => $item->id, 'quantity' => 3]);
        $this->post('/categories', [
            'code' => 'CT901',
            'name' => 'Staff cannot create this',
        ])->assertForbidden();
        $this->post("/items/{$item->id}/delete")->assertForbidden();
    }

    public function test_admin_can_manage_items_and_download_the_low_stock_pdf(): void
    {
        $admin = $this->makeUser('ADMIN');
        $category = Category::firstOrFail();
        $supplier = Supplier::firstOrFail();

        $this->actingAs($admin)->get('/items/create')->assertOk();
        $this->actingAs($admin)
            ->post('/items', [
                'sku' => 'ITM-900',
                'name' => 'Test item',
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'quantity' => 2,
                'reorder_level' => 5,
                'price' => '12.50',
            ])
            ->assertRedirect();

        $item = Item::where('sku', 'ITM-900')->firstOrFail();
        $this->get("/items/{$item->id}/update")->assertOk();
        $this->assertDatabaseHas('items', ['id' => $item->id, 'quantity' => 2]);

        $this->get('/reports/low-stock')
            ->assertOk()
            ->assertSee('ITM-900');

        $this->get('/reports/low-stock/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->post("/items/{$item->id}/delete")->assertRedirect(route('items.list'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_login_and_logout_use_the_session_authentication(): void
    {
        $user = $this->makeUser('STAFF');

        $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('items.list'));

        $this->assertAuthenticatedAs($user);

        $this->post('/auth/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restricting_category_deletion_shows_an_error_notification(): void
    {
        $category = Category::where('code', 'CT001')->firstOrFail();
        $this->actingAs($this->makeUser('ADMIN'));

        $this->from(route('categories.view', $category))
            ->post("/categories/{$category->id}/delete")
            ->assertRedirect(route('categories.view', $category))
            ->assertSessionHasErrors('alert');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    private function makeUser(string $role): User
    {
        $user = User::factory()->create();
        $user->role = $role;
        $user->save();

        return $user;
    }

    private function makeItem(): Item
    {
        $item = new Item([
            'sku' => 'ITM-TEST',
            'name' => 'Test stock item',
            'quantity' => 8,
            'reorder_level' => 4,
            'price' => '10.00',
        ]);
        $item->category()->associate(Category::firstOrFail());
        $item->supplier()->associate(Supplier::firstOrFail());
        $item->save();

        return $item;
    }
}
