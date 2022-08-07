<?php

namespace App\Console\Commands;

use App\Helpers\User\Comment\UserCommentValidator;
use App\Models\UserComment;
use Illuminate\Console\Command;

class WriteCommentCommand extends Command
{
    /**
     * Warning: In the docs, you are taught to put the dependencies in the `handle` method but I remember 
     * having this exact issue before from 2018:
     * https://github.com/laravel/framework/issues/14541
     */
    public function __construct(
        protected UserCommentValidator $userCommentValidator
    ) {
        if (isset($this->signature)) {
            $this->configureUsingFluentDefinition();
        } else {
            parent::__construct($this->name);
        }
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comment:write {user_id} {comment?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a comment for a specified user id. Specify the user id and comments enclosed in single quotes (\')';

    /**     
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $comment = $this->argument('comment');

        $this->userCommentValidator->assertRequestValid([
            'id' => $userId,
            'comments' => $comment
        ]);

        UserComment::create([
            'user_id' => $userId,
            'comment' => $comment
        ]);

        // As returned in the original controller.php
        $this->info('OK');

        return 0;
    }
}
