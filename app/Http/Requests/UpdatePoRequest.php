<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePoRequest extends FormRequest
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
            "empresa" => "required",
            "rif" => "required",
            "direccion" => "required",
            "postal" => "required",
            "file" => "mimes:jpg,jpeg,bmp,png,svg ",
        ];
    }
}
