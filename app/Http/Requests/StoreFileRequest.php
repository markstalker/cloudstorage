<?php

namespace App\Http\Requests;

use App\Rules\FileTypeRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'file' => ['required', 'max:20000', new FileTypeRule],
            'expires_at' =>'date',
            'folder_id' => 'integer|exists:folders,id',
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'Максимальный размер загружаемого файла - 20 МБ!',
        ];
    }
}
