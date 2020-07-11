<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

require_once 'htmlpurifier-4.13.0/library/HTMLPurifier.auto.php';

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request->post_text = cleanHtml($request->post_text ?? '');

        return [
            "post_title" => "required|max:256",
            "post_text" => "required",
        ];
    }
}