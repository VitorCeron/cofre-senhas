<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResourceCollection;
use App\Repositories\ActivityLogRepository;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     *
     * @var ActivityLogRepository
     */
    private $activityLogRepository;

    /**
     * Create a new UserPasswordsController instance.
     *
     * @return void
     */
    public function __construct(ActivityLogRepository $activityLogRepository) {
        $this->activityLogRepository = $activityLogRepository;
    }

    /**
     * Get all registers
     *
     * @return ActivityLogResourceCollection
     */
    public function get() {
        $data = $this->activityLogRepository->get();
        return new ActivityLogResourceCollection($data);
    }
}
