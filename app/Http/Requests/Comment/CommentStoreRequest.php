<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_text' => 'required|max:190',
            'postId' => 'required|numeric|exists:posts,id',
            'parent_comment_id' => 'present|nullable|numeric|exists:comments,id',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
