<?php

namespace App\Repositories;

use App\Models\UserPasswords;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class ActivityLogRepository {
    /**
     * Get all registers
     * 
     * @return Collection
     */
    public function get()
    {
        try {
            return QueryBuilder::for(Activity::class)
                ->allowedFilters(['description'])
                ->where('causer_id', Auth::user()->id)
                ->paginate();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}