<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\ForgotPasswordResource;
use App\Http\Resources\ResetPasswordInvalidTokenResource;
use App\Http\Resources\ResetPasswordResource;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * 
     * @return ForgotPasswordResource
     */
    public function forgot(ForgotPasswordRequest $credentials) {
        Password::sendResetLink($credentials->toArray());

        return new ForgotPasswordResource([
            'message' => 'Um link para atualizar sua senha foi enviado no seu e-mail'
        ]);
    }

    /**
     *
     * @return void
     */
    public function reset(ResetPasswordRequest $request) {
        $credentials = $request->toArray();

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return new ResetPasswordInvalidTokenResource([
                'message' => 'Token de recuperação de senha inválido'
            ]);
        }

        return new ResetPasswordResource([
            'message' => 'Senha atualizada com sucesso'
        ]);
    }
}
