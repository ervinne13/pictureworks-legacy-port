<?php

namespace App\Http\Controllers;

use App\Helpers\User\Comment\UserCommentValidator;
use App\Http\Requests\SaveUserCommentLegacyRequest;
use App\Http\Requests\SaveUserCommentRequest;
use Illuminate\Database\QueryException;

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

    public function storeWithLegacyRequest(SaveUserCommentLegacyRequest $request)
    {
        $this->userCommentLegacyValidator->assertRequestValid((array)$request->all());

        try {
            $request->getModel()->save();
        } catch (QueryException $e) {
            // As specified in the old controller.php
            return response("Could not update database: {$e->getMessage()}", 500);
        }

        return 'OK';
    }
}
