<?php

namespace Flute\Modules\DailyBonus\Widgets;

use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;
use Flute\Modules\DailyBonus\Database\Entities\UserBonus;

class DailyBonusWidget implements WidgetInterface
{
    public function getName(): string
    {
        return 'Ежедневный бонус';
    }

    public function getIcon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>';
    }

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

    public function render(array $settings): ?string
    {
        $daysCount = (int)($settings['days_count'] ?? 7);
        $bonusAmount = (float)($settings['bonus_amount'] ?? 100);
        $multiplierMode = (bool)($settings['multiplier_mode'] ?? false);
        $showTimer = (bool)($settings['show_timer'] ?? true);

        $dayRewards = [];
        if (isset($settings['day_rewards']) && is_string($settings['day_rewards'])) {
            $dayRewards = json_decode($settings['day_rewards'], true) ?? [];
        }

        $bonusDays = $this->generateBonusDays($daysCount, $bonusAmount, $multiplierMode, $dayRewards);
        $userBonus = $this->getUserBonusStatus();

        return view('dailybonus::widgets.daily-bonus', [
            'bonusDays' => $bonusDays,
            'userBonus' => $userBonus,
            'settings' => $settings,
            'showTimer' => $showTimer,
        ])->render();
    }

    protected function generateBonusDays(int $daysCount, float $baseAmount, bool $multiplierMode, array $dayRewards = []): array
    {
        $days = [];
        for ($day = 1; $day <= $daysCount; $day++) {
            $amount = $baseAmount;
            if (!empty($dayRewards) && isset($dayRewards[$day])) {
                $amount = (float) $dayRewards[$day];
            } elseif ($multiplierMode) {
                $amount = $baseAmount * $day;
            }
            $days[] = ['day' => $day, 'amount' => $amount, 'claimed' => false];
        }
        return $days;
    }

    protected function getUserBonusStatus(): array
    {
        if (!user()->isLoggedIn()) {
            return [
                'isLoggedIn' => false,
                'currentDay' => 0,
                'totalClaimed' => 0,
                'nextClaimTime' => null,
                'canClaim' => false,
            ];
        }

        $userId = user()->id;
        
        // Используем orm()->getRepository()
        $repository = orm()->getRepository(UserBonus::class);
        $lastBonus = $repository->findOne(['user_id' => $userId], [
            'orderBy' => ['claimed_at' => 'DESC']
        ]);

        $canClaim = true;
        $nextClaimTime = null;
        $currentDay = 1;

        if ($lastBonus) {
            $lastClaimDate = new \DateTime($lastBonus->claimed_at);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');

            if ($lastClaimDate >= $todayStart) {
                $canClaim = false;
                $tomorrow = (new \DateTime())->modify('+1 day');
                $tomorrowStart = new \DateTime($tomorrow->format('Y-m-d') . ' 00:00:00');
                $nextClaimTime = $tomorrowStart->getTimestamp() - $today->getTimestamp();
            }
            $currentDay = $lastBonus->day_number + 1;
        }

        $allBonuses = $repository->findAll(['user_id' => $userId]);
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

    public function renderSettingsForm(array $settings): string|bool
    {
        return view('dailybonus::settings-form', ['settings' => $settings])->render();
    }

    public function validateSettings(array $input): true|array
    {
        return true;
    }

    public function saveSettings(array $input): array
    {
        return $input;
    }

    public function getDefaultWidth(): int
    {
        return 4;
    }

    public function getMinWidth(): int
    {
        return 3;
    }

    public function hasSettings(): bool
    {
        return true;
    }

    public function getButtons(): array
    {
        return [];
    }

    public function handleAction(string $action, ?string $widgetId = null): array
    {
        return ['success' => false, 'message' => 'Unknown action'];
    }

    public function getCategory(): string
    {
        return 'content';
    }
}
