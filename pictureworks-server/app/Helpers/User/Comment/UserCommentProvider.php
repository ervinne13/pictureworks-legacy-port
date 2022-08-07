<?php

namespace App\Helpers\User\Comment;

use App\Models\UserComment;

interface UserCommentProvider
{
    public function getModel(): UserComment;
}