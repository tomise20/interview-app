<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CalculationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'valasztott-szak.egyetem' => 'required|string',
            'valasztott-szak.kar' => 'required|string',
            'erettsegi-eredmenyek' => 'required|array|min:4',
            'erettsegi-eredmenyek.*.nev' => 'required|string',
            'erettsegi-eredmenyek.*.tipus' => ['required', 'string'],
            'erettsegi-eredmenyek.*.eredmeny' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'valasztott-szak.egyetem.required' => 'Az egyetem megadása kötelező!.',
            'valasztott-szak.kar.required' => 'A kar megadása kötelező!',
            'erettsegi-eredmenyek.required' => 'Az érettségi eredmények megadása kötelező!',
            'erettsegi-eredmenyek.min' => 'Legalább a 3 kötelező és egy választhatóan kötelező trágyat meg kell adni!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json(['errors' => $errors->messages()], 422);

        throw new HttpResponseException($response);
    }
}
