<?php

namespace Tests\Feature\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserViewingFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function it_can_display_a_user_given_id()
    {
        $targetUserName = 'Ervinne Sodusta';
        $user = User::factory()->create(['name' => $targetUserName]);

        $response = $this->get("/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertSeeText($targetUserName);

        $expectedAsset = asset("images/users/{$user->id}.jpg");
        $response->assertSee("<img src='{$expectedAsset}' />", false);
    }
}
