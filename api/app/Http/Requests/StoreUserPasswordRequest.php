<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;

class StoreUserPasswordRequest extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'login' => 'required|string',
            'password' => 'required|string',
            'expire' => '',
            'link' => '',
            'observation' => '',
            'score_password' => '',
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
            'name.required' => 'O nome é obrigatório',
            'login.required' => 'O login é obrigatório',
            'password.required' => 'A senha é obrigatória',
            'user_id.required' => 'O usuário é obrigatório'
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
