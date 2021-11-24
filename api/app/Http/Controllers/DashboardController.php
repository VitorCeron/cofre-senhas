<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResourceCollection;
use App\Http\Resources\DashboardResource;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     *
     * @var DashboardRepository
     */
    private $dashboardRepository;

    /**
     * Create a new UserPasswordsController instance.
     *
     * @return void
     */
    public function __construct(DashboardRepository $dashboardRepository) {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * Get all registers
     *
     * @return ActivityLogResourceCollection
     */
    public function get() {
        $countUserPasswords = $this->dashboardRepository->getCountUserPasswordsByUser();
        $countDuplicatePasswords = $this->dashboardRepository->getDuplicatePasswords();
        $countPasswordsExpireIn30Days = $this->dashboardRepository->getPasswordsExpireIn30Days()->count();
        $averageScorePasswords = $this->dashboardRepository->getAverageScorePasswords();
        $listPasswordsExpireIn30Days = $this->dashboardRepository->getPasswordsExpireIn30Days();
        $colorScorePasswords = $this->getColorAndLabelScorePasswords(round($averageScorePasswords, 2))[0];
        $labelScorePasswords = $this->getColorAndLabelScorePasswords(round($averageScorePasswords, 2))[1];

        $data = [
            "count_user_passwords" => $countUserPasswords,
            "count_duplicate_passwords" => $countDuplicatePasswords,
            "count_passwords_expire_in_30_days" => $countPasswordsExpireIn30Days,
            "average_score_passwords" => round($averageScorePasswords, 2),
            "color_score_passwords" => $colorScorePasswords,
            "label_score_passwords" => $labelScorePasswords,
            "list_passwords_expire_in_30_days" => $listPasswordsExpireIn30Days,
        ];

        return new DashboardResource([
            'data' => $data
        ]);
    }

    private function getColorAndLabelScorePasswords($averageScorePasswords)
    {
        if ($averageScorePasswords > 80) { 
            return ["#21ba45", "Forte"];
        }

        if ($averageScorePasswords > 60) { 
            return ["#31ccec", "Bom"];
        }

        if ($averageScorePasswords > 30) { 
            return ["#f2c037", "Moderado"];
        }

        return ["#c10015", "Fraco"];
    }
}
