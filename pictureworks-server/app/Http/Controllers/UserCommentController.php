<?php

namespace App\Http\Controllers;

use App\Helpers\User\Comment\UserCommentValidator;
use App\Http\Requests\SaveUserCommentLegacyRequest;
use App\Http\Requests\SaveUserCommentRequest;
use App\Models\User;
use App\Models\UserComment;

class UserCommentController extends Controller
{

    public function __construct(
        protected UserCommentValidator $userCommentLegacyValidator
    ) {
    }

    public function store(SaveUserCommentRequest $request)
    {
        $request->getModel()->save();
        return 'OK';
    }

    /**
     * This offers 1:1 feature as the old controller except for 1 thing.
     * This test: it_fails_to_write_due_to_any_sql_error().
     * 
     * When it comes to database errors, sqlite does not throw errors even
     * if we put a string too large in it, moreover, since we are using the
     * facade directly for the UserComment, we can't mock it out with
     * Laravel's container. We can push to implement this as well 
     * BUT that would we we should create a repository for the comment insertion,
     * then mock that repository to throw an Illuminate\Database\QueryException.
     * 
     */
    public function storeWithLegacyRequest(SaveUserCommentLegacyRequest $request)
    {
        $this->userCommentLegacyValidator->assertRequestValid($request);
        UserComment::insert($request->getModels());

        return 'OK';
    }
}
