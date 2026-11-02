<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_homepage_uses_the_shared_layout(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('<title>Overview | StockWise</title>', false)
            ->assertSee('Inventory at a glance')
            ->assertSee('css/app.css');
    }

    public function test_the_shared_layout_displays_flash_notifications(): void
    {
        $this->withSession(['status' => 'Changes saved.'])
            ->get('/')
            ->assertSee('role="status"', false)
            ->assertSee('Changes saved.');
    }
}
