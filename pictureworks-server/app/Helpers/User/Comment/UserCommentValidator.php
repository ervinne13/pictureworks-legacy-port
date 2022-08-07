<?php

namespace App\Helpers\User\Comment;

use App\Helpers\Validation\LegacyValidator;
use App\Models\User;

/**
 * We can't use laravel's default validations as that returns
 * a different format, if we want a 1:1 error message copy 
 * of the legacy application, we seem to have the need to
 * create a custom validator.
 */
class UserCommentValidator
{
    public function __construct(
        protected LegacyValidator $validator
    ) {
    }

    /**
     * If there are actually more endpoints using the password validation, move it to a
     * middleware instead
     */
    public function assertRequestValid($request)
    {
        $this->validator->assertAttrComplete((array) $request->all(), ['id', 'comments', 'password']);
        $this->validator->assertPasswordCorrect($request['password']);
        $this->assertUserIdValid($request['id']);
        $this->assertUserExists($request['id']);
    }

    public function assertUserExists($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw CommentSaveException::fromUnregisteredUser($id);
        }
    }

    public function assertUserIdValid($id)
    {
        if (!is_numeric($id)) {
            throw CommentSaveException::fromInvalidUserId($id);
        }
    }
}
