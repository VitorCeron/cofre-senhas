<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\StoreUserPasswordRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Resources\LoginUserResource;
use App\Http\Resources\RegisterUserResource;
use App\Http\Resources\StoreUserPasswordResource;
use App\Http\Resources\UpdateUserPasswordResource;
use App\Http\Resources\UserPasswordsResource;
use App\Http\Resources\UserPasswordsResourceCollection;
use App\Models\User;
use App\Models\UserPasswords;
use App\Repositories\UserPasswordsRepository;
use Illuminate\Http\Request;

class UserPasswordsController extends Controller
{
    /**
     *
     * @var UserPasswordsRepository
     */
    private $userPasswordsRepository;

    /**
     * Create a new UserPasswordsController instance.
     *
     * @return void
     */
    public function __construct(UserPasswordsRepository $userPasswordsRepository) {
        $this->middleware('auth:api');
        $this->userPasswordsRepository = $userPasswordsRepository;
    }

    /**
     * Get all registers
     *
     * @return UserPasswordsResourceCollection
     */
    public function get() {
        $data = $this->userPasswordsRepository->get();
        return new UserPasswordsResourceCollection($data);
    }

    /**
     * Get register by id
     *
     * @param StoreUserPasswordRequest $request
     * @return UserPasswordsResource
     */
    public function getById(Request $request) {
        $data = $this->userPasswordsRepository->getById((int) $request->id);
        return new UserPasswordsResource($data);
    }

    /**
     * Store register
     * 
     * @param StoreUserPasswordRequest $request
     * @return StoreUserPasswordResource
     */
    public function store(StoreUserPasswordRequest $request) {
        $userPasswords = $this->userPasswordsRepository->store($request->validated());

        return new StoreUserPasswordResource([
            'user_password' => $userPasswords
        ]);
    }

    /**
     * Update register
     *
     * @param UpdateUserPasswordRequest $request
     * @return UpdateUserPasswordResource
     */
    public function update(UpdateUserPasswordRequest $request) {
        $userPassword = $this->userPasswordsRepository->update((int) $request->id, $request->all());

        return new UpdateUserPasswordResource([
            'user_password' => $userPassword
        ]);
    }

    /**
     * Destroy register
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        $userPassword = $this->userPasswordsRepository->destroy((int) $request->id);

        if($userPassword){
            return response()->json([])->setStatusCode(204);
        }
        
        return response()->json(['message' => 'Senha não existente'])->setStatusCode(404);
    }
}