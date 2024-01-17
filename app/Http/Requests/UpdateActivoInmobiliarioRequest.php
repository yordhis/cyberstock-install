<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivoInmobiliarioRequest extends FormRequest
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
            'codigo' => 'required|max:55', 
            'descripcion' => 'required|max:255', 
            'ubicacion' => 'required|max:255',
            'fecha_compra' => 'required|max:155',
            'cantidad'=> 'numeric|required|min:1|max:255',
            'costo' => 'numeric|required|min:1|max:255',
            'estatus' => ''
        ];
    }
}
