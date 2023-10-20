<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedoreRequest extends FormRequest
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
            "tipo_documento" => "required",
            "codigo" => "required|max:55|min: 4 ",
            "empresa" => "required | max:255 | min: 4",
            "rubro" => "required | max:255 | min: 4",
            "direccion" => "required | max:255 | min: 4",
            "file" => " mimes:jpg,jpeg,bmp,png,svg ",
            "edad" => "numeric",
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El campo Código es requerido.',
            'codigo.min' => 'El mínimo de longitud para el código son 4 digitos.',
            'codigo.max'=>'EL máximo de longitud del código es 55 digitos.',
            'tipo_documento.required' => "El campo Tipo de documento es requerido.",
            'empresa.required' => "El campo Empresa es requerido.",
            'empresa.max' => "EL máximo de longitud del campo Empresa es de 255 digitos.",
            'empresa.min' => "El mínimo de longitud para el campo Empresa es de 4 digitos.",
            'rubro.required' => "El campo Rubro es requerido.",
            'rubro.max' => "EL máximo de longitud del campo Rubro es de 255 digitos.",
            'rubro.min' => "El mínimo de longitud para el campo Rubro es de 4 digitos.",
            'direccion.required' => "El campo Dirección es requerido.",
            'direccion.max' => "EL máximo de longitud del campo Dirección es de 255 digitos.",
            'direccion.min' => "El mínimo de longitud para el campo Dirección es de 4 digitos.",
            'file.mimes' => "EL formato de la imagen no es valido, los formatos aceptados son: pg, jpeg, bmp, png, svg.",
            'file.max' => "EL máximo de longitud del campo File es de 255 digitos.",
        ];

    }
}
