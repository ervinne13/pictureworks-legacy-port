<?php

namespace App\Http\Requests;

use App\Helpers\User\Comment\UserCommentProvider;
use App\Models\UserComment;
use Illuminate\Foundation\Http\FormRequest;
use Pictureworks\AppKeyValidator;

/**
 * The objective is to present this to the proctors and negotiate that this implementation
 * be removed. This does not conform to laravel starndards as:
 *  - this gets the id from the request body instead of the route param: /users/{user}/comments
 *  - this accepts the password in the request body instead of the headers. Many would argue they are pretty much the same
 *    but if we instead put the app key as a Bearer token in the headers instead, we can control a whitelist easily in
 *    NGINX such that only allowed hosts may use this API directly from the frontend where the key is exposed.
 *  - on errors, the legacy system copy only sends the errors one at a time. Normally in laravel 
 *    the errors are placed in a json object where you can see each fields that failed.
 * 
 */
class SaveUserCommentLegacyRequest extends FormRequest
{

    public function authorize()
    {
        // We will use a custom authorization in the controller
        return true;
    }

    public function getModel(): UserComment
    {
        // If this was a PUT request, we load the comment instead, it's not
        // within the scope though, so we skip that.
        $comment = new UserComment();
        $comment->user_id = $this->id;
        $comment->comment = $this->comments;

        return $comment;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // We will handle the validation for this manually instead
        return [];
    }
}
