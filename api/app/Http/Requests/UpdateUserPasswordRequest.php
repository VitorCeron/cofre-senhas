<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;

class UpdateUserPasswordRequest extends FormRequest
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
            'name' => 'string|between:2,100',
            'login' => 'required|string',
            'password' => 'string',
            'expire' => '',
            'link' => '',
            'observation' => '',
            'user_id' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => 'O id do usuário é obrigatório'
        ];
    }

    /**
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }

    /**
     *
     * @return array
     */
    public function getData() {
        $data = $this->validated();
        $data['expire'] = $this->getExpire($data);
        return $data;
    }

    /**
     *
     * @param array $data
     * @return void
     */
    private function getExpire($data) {
        if(!array_key_exists('expire', $data) || !$data['expire']) {
            return null;
        }

        return Carbon::createFromFormat('d/m/Y', $data['expire'])->format('Y-m-d');
    }
}
