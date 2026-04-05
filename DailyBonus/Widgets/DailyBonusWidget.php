<?php

namespace Flute\Modules\DailyBonus\Widgets;

use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;

class DailyBonusWidget implements WidgetInterface
{
    /**
     * Название виджета
     */
    public function getName(): string
    {
        return 'Ежедневный бонус';
    }

    /**
     * Иконка виджета
     */
    public function getIcon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>';
    }

    /**
     * Настройки виджета
     */
    public function getSettings(): array
    {
        return [
            'bonus_amount' => [
                'type' => 'number',
                'label' => 'Сумма бонуса',
                'default' => 100,
                'min' => 1,
                'max' => 10000,
            ],
            'days_count' => [
                'type' => 'number',
                'label' => 'Количество дней',
                'default' => 7,
                'min' => 3,
                'max' => 30,
            ],
            'multiplier_mode' => [
                'type' => 'boolean',
                'label' => 'Режим множителя',
                'default' => false,
            ],
            'day_rewards' => [
                'type' => 'textarea',
                'label' => 'Награды по дням',
                'default' => '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}',
                'placeholder' => '{"1":100,"2":200,"3":300}',
            ],
            'show_timer' => [
                'type' => 'boolean',
                'label' => 'Показывать таймер',
                'default' => true,
            ],
        ];
    }

                'amount' => $amount,
                'claimed' => false,
            ];
        }

        return $days;
    }

    /**
     * Получение статуса бонусов пользователя из БД
     */
    protected function getUserBonusStatus(): array
    {
        // Проверяем авторизован ли пользователь
        if (!user()->isLoggedIn()) {
            return [
                'isLoggedIn' => false,
                'currentDay' => 0,
                'totalClaimed' => 0,
                'nextClaimTime' => null,
                'canClaim' => false,
            ];
        }

        $userId = user()->getIdentity();
        
        // Получаем последнюю запись о бонусе
        $lastBonus = UserBonus::select('*')
            ->where('user_id', $userId)
            ->orderBy('claimed_at', 'DESC')
            ->first();

        // Проверяем, можно ли получить бонус
        $canClaim = true;
        $nextClaimTime = null;
        $currentDay = 1;
        $totalClaimed = 0;

        if ($lastBonus) {
            $lastClaimDate = new \DateTime($lastBonus->claimed_at);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');

            if ($lastClaimDate >= $todayStart) {
                $canClaim = false;
                // Время до следующего дня
                $tomorrow = (new \DateTime())->modify('+1 day');
                $tomorrowStart = new \DateTime($tomorrow->format('Y-m-d') . ' 00:00:00');
                $nextClaimTime = $tomorrowStart->getTimestamp() - $today->getTimestamp();
            }
            
            $currentDay = $lastBonus->day_number + 1;
        }

        // Считаем общее количество полученных бонусов
        $allBonuses = UserBonus::select('*')
            ->where('user_id', $userId)
            ->all();
        
        $totalClaimed = 0;
        foreach ($allBonuses as $bonus) {
            $totalClaimed += $bonus->amount;
        }

        $claimCount = count($allBonuses);

        return [
            'isLoggedIn' => true,
            'currentDay' => $currentDay,
            'totalClaimed' => $totalClaimed,
            'nextClaimTime' => $nextClaimTime,
            'canClaim' => $canClaim,
            'claimCount' => $claimCount,
        ];
    }
     * Есть ли у виджета настройки
     */
    public function hasSettings(): bool
    {
        return true;
    }

    /**
     * Ширина виджета по умолчанию
     */
    public function getDefaultWidth(): int
    {
        return 12;
    }

    /**
     * Минимальная ширина
     */
    public function getMinWidth(): int
    {
        return 4;
    }

    /**
     * Категория виджета
     */
    public function getCategory(): string
    {
        return 'content';
    }

    /**
     * Валидация настроек
     */
    public function validateSettings(array $input): true|array
    {
        $validator = validator();

        $validated = $validator->validate($input, [
            'bonus_amount' => 'required|integer|min:1|max:10000',
            'days_count' => 'required|integer|min:3|max:30',
            'multiplier_mode' => 'boolean',
            'show_timer' => 'boolean',
        ]);

        if (!$validated) {
            return $validator->getErrors()->toArray();
        }

        return true;
    }

    /**
     * Сохранение настроек
     */
    public function saveSettings(array $input): array
    {
        return $input;
    }

    /**
     * Форма настроек виджета
     */
    public function renderSettingsForm(array $settings): string|bool
    {
        return false;
    }

    /**
     * Кнопки действий
     */
    public function getButtons(): array
    {
        return [
            [
                'title' => 'Claim Bonus',
                'action' => 'claim_bonus',
                'icon' => 'gift',
            ],
        ];
    }

    /**
     * Обработка действий
     */
    public function handleAction(string $action, ?string $widgetId = null): array
    {
        if ($action === 'claim_bonus') {
            return $this->processClaim();
        }

        return ['success' => false, 'message' => 'Unknown action'];
    }

    /**
     * Обработка получения бонуса
     */
    protected function processClaim(): array
    {
        if (!user()->isLoggedIn()) {
            return [
                'success' => false, 
                'message' => 'Please log in to claim your bonus'
            ];
        }

        $lastClaim = session()->get('daily_bonus_last_claim');
        
        if ($lastClaim) {
            $lastClaimDate = new \DateTime($lastClaim);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');
            
            if ($lastClaimDate >= $todayStart) {
                return [
                    'success' => false, 
                    'message' => 'You have already claimed your bonus today'
                ];
            }
        }

        // Обновляем данные
        $claimCount = session()->get('daily_bonus_claim_count', 0) + 1;
        $totalClaimed = session()->get('daily_bonus_total_claimed', 0) + 100;
        
        session()->set('daily_bonus_last_claim', (new \DateTime())->format('Y-m-d H:i:s'));
        session()->set('daily_bonus_claim_count', $claimCount);
        session()->set('daily_bonus_total_claimed', $totalClaimed);

        return [
            'success' => true,
            'message' => 'Bonus successfully claimed!',
            'data' => [
                'claim_count' => $claimCount,
                'total_claimed' => $totalClaimed,
            ],
        ];
    }
}
    /**
     * Отрисовка виджета
     */
    public function render(array $settings): string|null
    {
        $daysCount = (int)($settings['days_count'] ?? 7);
        $bonusAmount = (float)($settings['bonus_amount'] ?? 100);
        $multiplierMode = (bool)($settings['multiplier_mode'] ?? false);
        $showTimer = (bool)($settings['show_timer'] ?? true);

        // Парсим награды по дням из настроек
        $dayRewards = [];
        if (isset($settings['day_rewards']) && is_string($settings['day_rewards'])) {
            $dayRewards = json_decode($settings['day_rewards'], true) ?? [];
        }

        // Генерируем данные о днях
        $bonusDays = $this->generateBonusDays($daysCount, $bonusAmount, $multiplierMode, $dayRewards);

        // Получаем текущий статус пользователя
        $userBonus = $this->getUserBonusStatus();

        return view('dailybonus::widgets.daily-bonus', [
            'bonusDays' => $bonusDays,
            'userBonus' => $userBonus,
            'settings' => $settings,
            'showTimer' => $showTimer,
        ])->render();
    }

    /**
     * Генерация данных о днях бонуса
     */
    protected function generateBonusDays(int $daysCount, float $baseAmount, bool $multiplierMode, array $dayRewards = []): array
    {
        $days = [];

        for ($day = 1; $day <= $daysCount; $day++) {
            // Используем награду из настроек если указана
            $amount = $baseAmount;
            if (!empty($dayRewards) && isset($dayRewards[$day])) {
                $amount = (float) $dayRewards[$day];
            } elseif ($multiplierMode) {
                $amount = $baseAmount * $day;
            }

            $days[] = [
                'day' => $day,
                'amount' => $amount,
                'claimed' => false,
            ];
        }

        return $days;
    }
