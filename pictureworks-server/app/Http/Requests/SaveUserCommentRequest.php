<?php

namespace App\Http\Requests;

use App\Helpers\User\Comment\UserCommentProvider;
use App\Models\UserComment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Pictureworks\AppKeyValidator;

/**
 * We usually write this as "SaveXXX" instead of "StoreXXX" and "UpdateXXX".
 * The applicant, Ervinne, usually combines the two as they have similar validation
 * in them and we can just conditionally change if the request is PUT instead of POST.
 * 
 * The request actually serves two things here, validate the request, and form the
 * state shape (model) for the controller to process. Still, respect SRP and delegate
 * the transformation and validation rules elsewhere especially since we will
 * resuse the validations in a command later.
 */
class SaveUserCommentRequest extends FormRequest implements UserCommentProvider
{
    public function __construct(
        protected AppKeyValidator $appKeyValidator
    ) {
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // This will be where we should put validations like if the user
        // owns the comment being saved.
        return true;
    }

    public function getModel(): UserComment
    {
        // If this was a PUT request, we load the comment instead, it's not
        // within the scope though, so we skip that.
        $comment = new UserComment();
        $comment->user_id = $this->user->id;
        $comment->comment = $this->comment;

        return $comment;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'comment' => 'required|max:100'
        ];
    }

    public function messages()
    {
        // Uncomment this if the proctor wants the messages to at least follow
        // the old format
        return [
            // '*.required' => 'Missing key/value for ":attribute"',
        ];
    }
}
