<?php

namespace App\Http\Controllers;

use App\Helpers\User\Comment\UserCommentValidator;
use App\Helpers\Validation\LegacyValidationFailedException;
use App\Helpers\Validation\LegacyValidator;
use App\Http\Requests\SaveUserCommentLegacyRequest;
use App\Http\Requests\SaveUserCommentRequest;
use App\Models\UserComment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

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
        $this->userCommentLegacyValidator->assertRequestValid($request);
        UserComment::insert($request->getModels());

        return 'OK';
    }
}
