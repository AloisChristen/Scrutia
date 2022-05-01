<?php

namespace App\Http\Requests;

use App\Models\Answer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $answer = Answer::find($this->route()->parameter("id"));
        return auth()->user()->id == $answer->user->id || auth()->user()->reputation >= 150;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required|max:50",
            "content" => "required"
        ];
    }
}
