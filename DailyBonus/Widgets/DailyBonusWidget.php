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
        return __('dailybonus.widget_name');
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
                'label' => __('dailybonus.settings.bonus_amount'),
                'default' => 100,
                'min' => 1,
                'max' => 10000,
            ],
            'days_count' => [
                'type' => 'number',
                'label' => __('dailybonus.settings.days_count'),
                'default' => 7,
                'min' => 3,
                'max' => 30,
            ],
            'multiplier_mode' => [
                'type' => 'boolean',
                'label' => __('dailybonus.settings.multiplier_mode'),
                'default' => false,
            ],
            'show_timer' => [
                'type' => 'boolean',
                'label' => __('dailybonus.settings.show_timer'),
                'default' => true,
            ],
        ];
    }

    /**
     * Отрисовка виджета
     */
    public function render(array $settings): string|null
    {
        $daysCount = $settings['days_count'] ?? 7;
        $bonusAmount = $settings['bonus_amount'] ?? 100;
        $multiplierMode = $settings['multiplier_mode'] ?? false;
        $showTimer = $settings['show_timer'] ?? true;

        // Генерируем данные о днях
        $bonusDays = $this->generateBonusDays($daysCount, $bonusAmount, $multiplierMode);

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
    protected function generateBonusDays(int $daysCount, float $baseAmount, bool $multiplierMode): array
    {
        $days = [];
        
        for ($day = 1; $day <= $daysCount; $day++) {
            $amount = $multiplierMode ? $baseAmount * $day : $baseAmount;
            
            $days[] = [
                'day' => $day,
                'amount' => $amount,
                'claimed' => false,
            ];
        }

        return $days;
    }

    /**
     * Получение статуса бонусов пользователя
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

        // Получаем данные пользователя из сессии/БД
        // Для демонстрации используем сессию
        $lastClaim = session()->get('daily_bonus_last_claim');
        $claimCount = session()->get('daily_bonus_claim_count', 0);
        
        $currentDay = ($claimCount % 7) + 1;
        $totalClaimed = session()->get('daily_bonus_total_claimed', 0);

        // Проверяем, можно ли получить бонус
        $canClaim = true;
        $nextClaimTime = null;

        if ($lastClaim) {
            $lastClaimDate = new \DateTime($lastClaim);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');
            
            if ($lastClaimDate >= $todayStart) {
                $canClaim = false;
                // Время до следующего дня
                $tomorrow = (new \DateTime())->modify('+1 day');
                $tomorrowStart = new \DateTime($tomorrow->format('Y-m-d') . ' 00:00:00');
                $nextClaimTime = $tomorrowStart->getTimestamp() - $today->getTimestamp();
            }
        }

        return [
            'isLoggedIn' => true,
            'currentDay' => $currentDay,
            'totalClaimed' => $totalClaimed,
            'nextClaimTime' => $nextClaimTime,
            'canClaim' => $canClaim,
            'claimCount' => $claimCount,
        ];
    }

    /**
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
        return 'rewards';
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
        return view('dailybonus::widgets.daily-bonus-settings', [
            'settings' => $settings,
        ])->render();
    }

    /**
     * Кнопки действий
     */
    public function getButtons(): array
    {
        return [
            [
                'title' => __('dailybonus.actions.claim'),
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

        return ['success' => false, 'message' => __('dailybonus.errors.unknown_action')];
    }

    /**
     * Обработка получения бонуса
     */
    protected function processClaim(): array
    {
        if (!user()->isLoggedIn()) {
            return [
                'success' => false, 
                'message' => __('dailybonus.errors.not_logged_in')
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
                    'message' => __('dailybonus.errors.already_claimed')
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
            'message' => __('dailybonus.success.claimed'),
            'data' => [
                'claim_count' => $claimCount,
                'total_claimed' => $totalClaimed,
            ],
        ];
    }
}