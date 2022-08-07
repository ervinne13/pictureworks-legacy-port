<?php

namespace App\Helpers\User\Comment;

use Exception;

enum CommentSaveExceptionCode
{
    const UNREGISTERED_USER = 1;
}

class CommentSaveException extends Exception
{
    public static function fromUnregisteredUser($userId): CommentSaveException
    {
        return new CommentSaveException("No such user ({$userId})", CommentSaveExceptionCode::UNREGISTERED_USER);
    }
}
