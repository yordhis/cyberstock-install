<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarcaRequest extends FormRequest
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
            'nombre' => "required | max:255 "
        ];
    }
    public function messages()
    {
        return [
            // "nombre.required" => "El campo Nombre de la marca es requerido.",
            // "nombre.max" => "El texto ingresado superar la longitud de caracteres permitidos."
        ];
    }
}
