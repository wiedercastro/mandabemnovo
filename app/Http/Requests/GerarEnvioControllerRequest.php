<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GerarEnvioControllerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'CEP' => 'required|regex:/^\d{5}-\d{3}$/',
            'complemento'=> 'max:20',
            'peso' => 'required|not_in:0',
            'estado' => 'required|not_in:0',
            'email' => 'nullable|email',
        ];
    }
    public function messages(): array
    {
        return [
            'CEP.required' => 'O campo CEP é obrigatório.',
            'complemento.max' => 'Somente 20 caracteres',
            'peso' => 'Selecione um Peso',
            'estado' => 'Selecione um Estado',
            'email' => 'O email está incorreto',
            // Adicione mais mensagens conforme necessário
        ];
    }

}
