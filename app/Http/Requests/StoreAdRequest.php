<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
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
        return [
            'title' => 'required|max:64',
            'description' => 'required',
            'price' => 'required|max:40',
            'category' => 'required|exists:categories,id',
            'address' => 'required|max:64',
            'images' => 'required|max:4'
        ];
    }
}
