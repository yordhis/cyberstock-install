<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUtilityRequest extends FormRequest
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
            "iva" => "required| max:16 | min:0",
            "pvp_1" => "required| max:2000 | min:0",
            "pvp_2" => "required| max:2000 | min:0",
            "pvp_3" => "required| max:2000 | min:0",
            "tasa" => "required| max:2000 | min:0",
        ];
    }
}
