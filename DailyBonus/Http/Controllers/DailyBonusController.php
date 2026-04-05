<?php

namespace Flute\Modules\DailyBonus\Http\Controllers;

use Flute\Core\Support\BaseController;
use Flute\Core\Router\Annotations\Route;
use Flute\Modules\DailyBonus\Database\Entities\UserBonus;

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

        $userId = user()->getIdentity();
        
        // Получаем последнюю запись о бонусе
        $lastBonus = UserBonus::select('*')
            ->where('user_id', $userId)
            ->orderBy('claimed_at', 'DESC')
            ->first();

        // Проверяем, получал ли уже бонус сегодня
        if ($lastBonus) {
            $lastClaimDate = new \DateTime($lastBonus->claimed_at);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');

            if ($lastClaimDate >= $todayStart) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already claimed your bonus today'
                ]);
            }
        }

        // Получаем текущий баланс пользователя
        $currentUser = user()->getUser();
        $currentBalance = (float) ($currentUser->balance ?? 0);
        
        // Сумма бонуса (можно сделать настраиваемой)
        $bonusAmount = 100;
        
        // Обновляем баланс
        $currentUser->balance = $currentBalance + $bonusAmount;
        $currentUser->save();

        // Записываем в историю бонусов
        $bonusRecord = new UserBonus();
        $bonusRecord->user_id = $userId;
        $bonusRecord->amount = $bonusAmount;
        $bonusRecord->day_number = ($lastBonus ? $lastBonus->day_number + 1 : 1);
        $bonusRecord->claimed_at = (new \DateTime())->format('Y-m-d H:i:s');
        $bonusRecord->save();

        return response()->json([
            'success' => true,
            'message' => 'Bonus successfully claimed!',
            'data' => [
                'amount' => $bonusAmount,
                'new_balance' => $currentUser->balance,
                'day_number' => $bonusRecord->day_number,
            ]
        ]);
    }
}
