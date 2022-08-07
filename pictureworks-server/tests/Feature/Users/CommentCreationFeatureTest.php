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

        $pw = env("UNIT_TEST_APPLICATION_KEY");
        $ts = Carbon::now()->toDateTimeString();
        $comment = "This is a test comment {$ts}";

        $response = $this->postJson("api/v1/users/{$user->id}/comments", [
            'comment' => $comment,
            'password' => $pw
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("OK");
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('user_comments', [
            'user_id' => $user->id,
            'comment' => $comment
        ]);
    }

    /**
     * @test
     */
    public function it_fails_to_write_a_comment_if_password_is_invalid()
    {
        $user = User::factory()->create();

        $response = $this->postJson("api/v1/users/{$user->id}/comments", [
            'comment' => "Doesn't matter, will fail anyway"
        ]);

        $response->assertStatus(401);
        $content = $response->getContent();
        $this->assertEquals('Invalid password', $content);

        $response = $this->postJson("api/v1/users/{$user->id}/comments", [
            'comment' => "Doesn't matter, will fail anyway",
            'password' => 'something invalid'
        ]);

        $response->assertStatus(401);
        $content = $response->getContent();
        $this->assertEquals('Invalid password', $content);
    }

    /**
     * @test
     */
    public function it_fails_to_write_comment_not_provided()
    {

        $user = User::factory()->create();

        $pw = env("UNIT_TEST_APPLICATION_KEY");
        $response = $this->postJson("api/v1/users/{$user->id}/comments", [
            'password' => $pw
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_fails_to_write_comments_too_large()
    {
        $user = User::factory()->create();

        $pw = env("UNIT_TEST_APPLICATION_KEY");
        $comment = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
        molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
        numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
        optio, eaque rerum! Provident similique accusantium nemo autem. Veritatis
        obcaecati tenetur iure eius earum ut molestias architecto voluptate aliquam
        nihil, eveniet aliquid culpa officia aut! Impedit sit sunt quaerat, odit";

        $response = $this->postJson("api/v1/users/{$user->id}/comments", [
            'comment' => $comment,
            'password' => $pw
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['comment']);
    }

    /**
     * @test
     */
    public function it_fails_to_write_comment_for_non_existent_user()
    {
        $response = $this->postJson("api/v1/users/1/comments", [
            'comment' => "Doesn't matter, will fail anyway",
            'password' => env("UNIT_TEST_APPLICATION_KEY")
        ]);
        $response->assertStatus(404);
    }
}
