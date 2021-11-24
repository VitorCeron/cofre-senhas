<?php

namespace App\Repositories;

use App\Models\UserPasswords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardRepository {
    /**
     * Get all registers
     * 
     * @return Collection
     */
    public function getCountUserPasswordsByUser()
    {
        try {
            return QueryBuilder::for(UserPasswords::class)
                ->where('user_id', Auth::user()->id)
                ->count();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDuplicatePasswords() 
    {
        $duplicates = QueryBuilder::for(UserPasswords::class)
            ->select('id', 'password')
            ->whereIn('password', function ($q){
                $q->select('password')
                    ->from('user_passwords')
                    ->groupBy('password')
                    ->where('user_id', Auth::user()->id)
                    ->havingRaw('COUNT(*) > 1');
                })
            ->orderBy('password')
            ->orderBy('id')
            ->where('user_id', Auth::user()->id)
            ->count();

        return $duplicates / 2;
    }

    public function getPasswordsExpireIn30Days() 
    {
        return QueryBuilder::for(UserPasswords::class)
                ->where('user_id', Auth::user()->id)
                ->where('expire', '<', Carbon::now()->addDays(30))
                ->get();
    }

    public function getAverageScorePasswords() 
    {
        return QueryBuilder::for(UserPasswords::class)
            ->where('user_id', Auth::user()->id)
            ->avg('score_password');
    }
}