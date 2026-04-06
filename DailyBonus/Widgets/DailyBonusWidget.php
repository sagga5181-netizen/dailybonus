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
                'min' => 1,
                'max' => 30,
            ],
            'reward_type' => [
                'type' => 'select',
                'label' => 'Тип награды',
                'default' => 'balance',
                'options' => [
                    'balance' => 'Баланс',
                    'coins' => 'Монеты',
                    'points' => 'Поинты',
                ],
            ],
            'cycle_enabled' => [
                'type' => 'boolean',
                'label' => 'Цикличность (сброс после N дней)',
                'default' => true,
            ],
            'reset_on_miss' => [
                'type' => 'boolean',
                'label' => 'Сбросить прогресс если пропущен день',
                'default' => true,
            ],
            'show_timer' => [
                'type' => 'boolean',
                'label' => 'Показывать таймер',
                'default' => true,
            ],
            'show_stats' => [
                'type' => 'boolean',
                'label' => 'Показывать статистику',
                'default' => true,
            ],
        ];
    }

    public function render(array $settings): ?string
    {
        $daysCount = (int)($settings['days_count'] ?? 7);
        $bonusAmount = (float)($settings['bonus_amount'] ?? 100);
        $rewardType = $settings['reward_type'] ?? 'balance';
        $cycleEnabled = (bool)($settings['cycle_enabled'] ?? true);
        $resetOnMiss = (bool)($settings['reset_on_miss'] ?? true);
        $showTimer = (bool)($settings['show_timer'] ?? true);
        $showStats = (bool)($settings['show_stats'] ?? true);

        $dayRewards = [];
        if (isset($settings['day_rewards']) && is_string($settings['day_rewards'])) {
            $dayRewards = json_decode($settings['day_rewards'], true) ?? [];
        } elseif (is_array($settings['day_rewards'] ?? [])) {
            $dayRewards = $settings['day_rewards'];
        }

        $bonusDays = $this->generateBonusDays($daysCount, $bonusAmount, $dayRewards);
        $userBonus = $this->getUserBonusStatus($daysCount, $cycleEnabled, $resetOnMiss);

        return view('dailybonus::widgets.daily-bonus', [
            'bonusDays' => $bonusDays,
            'userBonus' => $userBonus,
            'settings' => $settings,
            'showTimer' => $showTimer,
            'showStats' => $showStats,
            'rewardType' => $rewardType,
        ])->render();
    }

    protected function generateBonusDays(int $daysCount, float $baseAmount, ?array $dayRewards = null): array
    {
        $dayRewards = $dayRewards ?? [];
        $days = [];
        for ($day = 1; $day <= $daysCount; $day++) {
            $amount = $baseAmount;
            if (!empty($dayRewards) && isset($dayRewards[$day])) {
                $amount = (float) $dayRewards[$day];
            }
            $days[] = ['day' => $day, 'amount' => $amount, 'claimed' => false];
        }
        return $days;
    }

    protected function getUserBonusStatus(int $daysCount, bool $cycleEnabled, bool $resetOnMiss): array
    {
        if (!user()->isLoggedIn()) {
            return [
                'isLoggedIn' => false,
                'currentDay' => 1,
                'totalClaimed' => 0,
                'nextClaimTime' => null,
                'canClaim' => false,
                'claimCount' => 0,
                'cycleCount' => 1,
                'progress' => 0,
            ];
        }

        $userId = user()->id;
        
        $repository = orm()->getRepository(UserBonus::class);
        $lastBonus = $repository->findOne(['user_id' => $userId], [
            'orderBy' => ['claimed_at' => 'DESC']
        ]);

        $canClaim = true;
        $nextClaimTime = null;
        $currentDay = 1;
        $cycleCount = 1;
        $totalClaimed = 0;
        $claimCount = 0;

        if ($lastBonus) {
            $lastClaimDate = new \DateTime($lastBonus->claimed_at);
            $today = new \DateTime();
            $todayStart = new \DateTime($today->format('Y-m-d') . ' 00:00:00');
            
            $cycleCount = $lastBonus->cycle_count ?? 1;

            // Check if already claimed today
            if ($lastClaimDate >= $todayStart) {
                $canClaim = false;
                $tomorrow = (new \DateTime())->modify('+1 day');
                $tomorrowStart = new \DateTime($tomorrow->format('Y-m-d') . ' 00:00:00');
                $nextClaimTime = $tomorrowStart->getTimestamp() - $today->getTimestamp();
                $currentDay = $lastBonus->day_number;
            } else {
                // Check if missed a day
                $yesterday = (new \DateTime())->modify('-1 day');
                $yesterdayStart = new \DateTime($yesterday->format('Y-m-d') . ' 00:00:00');
                
                if ($lastClaimDate < $yesterdayStart && $resetOnMiss) {
                    // Missed a day, reset progress
                    $currentDay = 1;
                    $cycleCount = $cycleCount + 1;
                } else {
                    // Continue from current day
                    $currentDay = $lastBonus->day_number + 1;
                    
                    // Check if cycle complete
                    if ($currentDay > $daysCount && $cycleEnabled) {
                        $currentDay = 1;
                        $cycleCount = $cycleCount + 1;
                    } elseif ($currentDay > $daysCount) {
                        $currentDay = $daysCount;
                    }
                }
            }
        }

        // Get all bonuses for stats
        $allBonuses = $repository->findAll(['user_id' => $userId]);
        foreach ($allBonuses as $bonus) {
            $totalClaimed += $bonus->amount;
        }
        $claimCount = count($allBonuses);

        // Calculate progress percentage
        $progress = $claimCount > 0 ? min(100, ($claimCount / $daysCount) * 100) : 0;

        return [
            'isLoggedIn' => true,
            'currentDay' => $currentDay,
            'totalClaimed' => $totalClaimed,
            'nextClaimTime' => $nextClaimTime,
            'canClaim' => $canClaim,
            'claimCount' => $claimCount,
            'cycleCount' => $cycleCount,
            'progress' => $progress,
            'daysCount' => $daysCount,
            'cycleEnabled' => $cycleEnabled,
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
        // Ensure day_rewards is properly formatted
        if (isset($input['day_rewards']) && is_array($input['day_rewards'])) {
            $input['day_rewards'] = json_encode($input['day_rewards']);
        }
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
        if ($action === 'claim') {
            return $this->handleClaim($widgetId);
        }
        
        return ['success' => false, 'message' => 'Unknown action'];
    }

    protected function handleClaim(?string $widgetId = null): array
    {
        if (!user()->isLoggedIn()) {
            return ['success' => false, 'message' => 'Необходимо войти в аккаунт'];
        }

        // Get widget settings
        $settings = $this->getSettings();
        
        // Try to get actual settings from database
        try {
            $widgetRepo = orm()->getRepository(\Flute\Core\Modules\Page\Widgets\Widget::class);
            if ($widgetId && $widgetRepo) {
                $widget = $widgetRepo->findByPK($widgetId);
                if ($widget && isset($widget->settings)) {
                    $settings = array_merge($settings, $widget->settings);
                }
            }
        } catch (\Exception $e) {
            // Use defaults
        }

        $daysCount = (int)($settings['days_count'] ?? 7);
        $bonusAmount = (float)($settings['bonus_amount'] ?? 100);
        $rewardType = $settings['reward_type'] ?? 'balance';
        $cycleEnabled = (bool)($settings['cycle_enabled'] ?? true);
        $resetOnMiss = (bool)($settings['reset_on_miss'] ?? true);

        $dayRewards = [];
        if (isset($settings['day_rewards']) && is_string($settings['day_rewards'])) {
            $dayRewards = json_decode($settings['day_rewards'], true) ?? [];
        }

        $userBonus = $this->getUserBonusStatus($daysCount, $cycleEnabled, $resetOnMiss);

        if (!$userBonus['canClaim']) {
            return ['success' => false, 'message' => 'Награда уже получена сегодня'];
        }

        $currentDay = $userBonus['currentDay'];
        $amount = $bonusAmount;
        
        // Get day-specific amount if set
        if (!empty($dayRewards) && isset($dayRewards[$currentDay])) {
            $amount = (float) $dayRewards[$currentDay];
        }

        // Add to user balance
        $this->addToBalance(user()->id, $amount, $rewardType);

        // Save to history
        $this->saveBonusHistory(user()->id, $amount, $currentDay, $userBonus['cycle_count'], $rewardType);

        // Log the reward
        $this->logReward(user()->id, $amount, $currentDay, $rewardType);

        return [
            'success' => true,
            'message' => 'Награда получена! +' . $amount . ' ' . $this->getRewardTypeLabel($rewardType),
            'data' => [
                'amount' => $amount,
                'day' => $currentDay,
                'totalClaimed' => $userBonus['totalClaimed'] + $amount,
            ]
        ];
    }

    protected function addToBalance(int $userId, float $amount, string $rewardType): bool
    {
        try {
            switch ($rewardType) {
                case 'balance':
                    if (method_exists(user(), 'addBalance')) {
                        user()->addBalance($amount);
                    } else {
                        // Try direct update
                        $this->updateUserBalance($userId, $amount);
                    }
                    break;
                case 'coins':
                    if (method_exists(user(), 'addCoins')) {
                        user()->addCoins($amount);
                    }
                    break;
                case 'points':
                    if (method_exists(user(), 'addPoints')) {
                        user()->addPoints($amount);
                    }
                    break;
                default:
                    $this->updateUserBalance($userId, $amount);
            }
            return true;
        } catch (\Exception $e) {
            error_log("DailyBonus: Failed to add balance - " . $e->getMessage());
            return false;
        }
    }

    protected function updateUserBalance(int $userId, float $amount): void
    {
        try {
            $userRepo = orm()->getRepository(\Flute\Database\Entities\User::class);
            $user = $userRepo->findByPK($userId);
            if ($user && isset($user->balance)) {
                $user->balance = ($user->balance ?? 0) + $amount;
                orm()->getRepository(\Flute\Database\Entities\User::class)->update($user);
            }
        } catch (\Exception $e) {
            error_log("DailyBonus: Failed to update balance - " . $e->getMessage());
        }
    }

    protected function saveBonusHistory(int $userId, float $amount, int $dayNumber, int $cycleCount, string $rewardType): void
    {
        try {
            $repository = orm()->getRepository(UserBonus::class);
            
            $bonus = new UserBonus();
            $bonus->user_id = $userId;
            $bonus->amount = $amount;
            $bonus->day_number = $dayNumber;
            $bonus->cycle_count = $cycleCount;
            $bonus->reward_type = $rewardType;
            $bonus->reward_item = null;
            $bonus->claimed_at = (new \DateTime())->format('Y-m-d H:i:s');
            
            $repository->create($bonus);
        } catch (\Exception $e) {
            error_log("DailyBonus: Failed to save history - " . $e->getMessage());
        }
    }

    protected function logReward(int $userId, float $amount, int $day, string $rewardType): void
    {
        $logMessage = sprintf(
            "[%s] DailyBonus: User #%d claimed day %d, amount: %f, type: %s",
            date('Y-m-d H:i:s'),
            $userId,
            $day,
            $amount,
            $rewardType
        );
        error_log($logMessage);
    }

    protected function getRewardTypeLabel(string $rewardType): string
    {
        $labels = [
            'balance' => '₽',
            'coins' => 'монет',
            'points' => 'поинтов',
        ];
        return $labels[$rewardType] ?? '₽';
    }

    public function getCategory(): string
    {
        return 'content';
    }
}
