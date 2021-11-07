<?php

namespace App\Repositories;

use App\Models\UserPasswords;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class ActivityLogRepository {

    private $userId;

    /**
     * Create a new UserPasswordsController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->userId = Auth::user()->id;
    }

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
                ->where('causer_id', $this->userId)
                ->paginate();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}