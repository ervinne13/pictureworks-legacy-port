<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class CommentCreationFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_write_a_comment_for_a_user()
    {
        $targetUserName = 'Ervinne Sodusta';
        $user = User::factory()->create(['name' => $targetUserName]);

        $pw = env("APPLICATION_KEY");
        $ts = Carbon::now()->toDateTimeString();
        $comment = "This is a test comment {$ts}";

        $response = $this->post("api/v1/users/{$user->id}/comments", [
            'comment' => $comment,
            'password' => $pw
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("OK");

        $this->assertDatabaseHas('user_comments', [
            'user_id' => $user->id,
            'comment' => $comment
        ]);
    }
}
