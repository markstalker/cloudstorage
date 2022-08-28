<?php

namespace App\Http\Requests;

use App\Rules\FileTypeRule;
use App\Rules\HasQuotaRule;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => ['required', 'max:20000', new FileTypeRule, new HasQuotaRule],
            'expires_at' =>'date',
            'folder_id' => [
                'integer',
                Rule::exists('folders', 'id')->where(fn ($query) => $query->whereUserId(Auth::user()->id))
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'Максимальный размер загружаемого файла - 20 МБ!',
        ];
    }
}
