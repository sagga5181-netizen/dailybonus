<?php

namespace Flute\Modules\DailyBonus\Http\Controllers;

use Flute\Core\Support\BaseController;
use Flute\Core\Router\Annotations\Route;

class DailyBonusController extends BaseController
{
    #[Route('/dailybonus/claim', name: 'dailybonus.claim', methods: ['POST'])]
    public function claim()
    {
        if (!user()->isLoggedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to claim your bonus'
            ]);
        }

        $lastClaim = session()->get('daily_bonus_last_claim');
        
        if ($lastClaim) {
            $lastClaimDate = new \DateTime($lastClaim);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');
            
            if ($lastClaimDate >= $todayStart) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already claimed your bonus today'
                ]);
            }
        }

        // Обновляем данные пользователя
        $claimCount = session()->get('daily_bonus_claim_count', 0) + 1;
        $totalClaimed = session()->get('daily_bonus_total_claimed', 0) + 100; // Базовая сумма
        
        session()->set('daily_bonus_last_claim', (new \DateTime())->format('Y-m-d H:i:s'));
        session()->set('daily_bonus_claim_count', $claimCount);
        session()->set('daily_bonus_total_claimed', $totalClaimed);

        return response()->json([
            'success' => true,
            'message' => 'Bonus successfully claimed!',
            'data' => [
                'claim_count' => $claimCount,
                'total_claimed' => $totalClaimed,
            ]
        ]);
    }
}