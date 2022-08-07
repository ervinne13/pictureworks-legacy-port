<?php

namespace Tests\Feature\Users;

use App\Http\Controllers\UserCommentController;
use App\Models\User;
use App\Models\UserComment;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CommentCreationLegacyParamsFeatureTest extends TestCase
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

        $response = $this->post("api/v1/comments", [
            'id' => $user->id,
            'comments' => $comment,
            'password' => $pw
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("OK");

        $this->assertDatabaseHas('user_comments', [
            'user_id' => $user->id,
            'comment' => $comment
        ]);
    }

    /**
     * @test
     */
    public function it_can_write_multiple_comments_for_a_user_via_json()
    {
        $targetUserName = 'Ervinne Sodusta';
        $user = User::factory()->create(['name' => $targetUserName]);

        $pw = env("UNIT_TEST_APPLICATION_KEY");
        $comments = [];
        for ($i = 1; $i <= 10; $i++) {
            $comments[] = "This is comment #{$i}";
        }

        $response = $this->postJson("api/v1/comments", [
            'id' => $user->id,
            'comments' => $comments,
            'password' => $pw
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("OK");

        foreach ($comments as $comment) {
            $this->assertDatabaseHas('user_comments', [
                'user_id' => $user->id,
                'comment' => $comment
            ]);
        }
    }

    /**
     * @test
     */
    public function it_fails_to_write_if_password_is_not_provided()
    {
        $response = $this->post("api/v1/comments", [
            'id' => 1,
            'comments' => "Doesn't matter, should fail"
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_fails_to_write_if_password_is_not_correct()
    {
        $response = $this->post("api/v1/comments", [
            'id' => 1,
            'comments' => "Doesn't matter, should fail",
            'password' => 'something'
        ]);

        $response->assertStatus(401);
        $content = $response->getContent();
        $this->assertEquals('Invalid password', $content);
    }

    /**
     * @test
     */
    public function it_fails_to_write_if_id_is_invalid()
    {
        $response = $this->post("api/v1/comments", [
            'id' => 'adfasdf',
            'comments' => "Doesn't matter, should fail",
            'password' => env("UNIT_TEST_APPLICATION_KEY")
        ]);

        $response->assertStatus(422);
        $content = $response->getContent();
        $this->assertEquals('Invalid id', $content);
    }

    /**
     * @test
     */
    public function it_fails_to_write_comment_to_non_existent_user()
    {
        $response = $this->post("api/v1/comments", [
            'id' => 1,
            'comments' => "Doesn't matter, should fail",
            'password' => env("UNIT_TEST_APPLICATION_KEY")
        ]);

        $response->assertStatus(404);
        $content = $response->getContent();

        // This was hardcoded in the old system to be 3, but let's get the idea
        // and return the actual user's id that we tried to write to.
        $this->assertEquals('No such user (1)', $content);
    }

    /**
     * @test
     */
    public function it_fails_to_write_if_there_are_missing_values()
    {
        $response = $this->postJson("api/v1/comments", [
            'comments' => "Doesn't matter, should fail",
            'password' => env("UNIT_TEST_APPLICATION_KEY")
        ]);

        $response->assertStatus(422);
        $content = $response->getContent();
        $this->assertEquals('Missing key/value for "id"', $content);
    }
}
