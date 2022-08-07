<?php

namespace App\Helpers\User\Comment;

use Exception;

enum CommentSaveExceptionCode
{
    const INVALID_ID = 1;
    const UNREGISTERED_USER = 2;
}

class CommentSaveException extends Exception
{
    public static function fromInvalidUserId($userId): CommentSaveException
    {
        return new CommentSaveException("Invalid id", CommentSaveExceptionCode::INVALID_ID);
    }

    public static function fromUnregisteredUser($userId): CommentSaveException
    {
        return new CommentSaveException("No such user ({$userId})", CommentSaveExceptionCode::UNREGISTERED_USER);
    }
}
