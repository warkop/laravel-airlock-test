<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Airlock\Airlock;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_task_list_can_be_retrieved()
    {
        Airlock::actingAs(
            factory(User::class)->create(),
            ['*']
        );

        $response = $this->get('/api/user');

        $response->assertOk();
    }
}
