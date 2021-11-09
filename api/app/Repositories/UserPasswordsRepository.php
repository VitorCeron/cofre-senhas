<?php

namespace App\Repositories;

use App\Models\UserPasswords;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class UserPasswordsRepository {
    /**
     * Get all registers
     * 
     * @return Collection
     */
    public function get()
    {
        try {
            return QueryBuilder::for(UserPasswords::class)
                ->allowedFilters(['name'])
                ->where('user_id', Auth::user()->id)
                ->paginate();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get register by id
     *
     * @param integer $id
     * @return UserPasswords
     */
    public function getById(int $id) {
        try {
            return QueryBuilder::for(UserPasswords::class)
                ->allowedFilters(['name'])
                ->where('user_id', Auth::user()->id)
                ->where('id', $id)
                ->first();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store register
     *
     * @param array $data
     * @return UserPasswords
     */
    public function store(array $data)
    {
        try {
            return UserPasswords::create($data);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update register
     *
     * @param integer $id
     * @param array $data
     * @return UserPasswords
     */
    public function update(int $id, array $data) 
    {
        try {
            $userPassword = $this->getById($id);
            return $userPassword->update($data);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Destroy register
     *
     * @param integer $id
     * @return void
     */
    public function destroy(int $id) 
    {
        try {
            return UserPasswords::destroy($id);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}