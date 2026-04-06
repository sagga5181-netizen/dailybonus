<?php

namespace Flute\Modules\DailyRewards\Services;

use Flute\Modules\DailyRewards\Database\Entities\DailyRewardConfig;
use Flute\Modules\DailyRewards\Database\Entities\DailyReward;
use Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser;
use Flute\Modules\DailyRewards\Database\Entities\DailyRewardHistory;

class DailyRewardsService
{
    protected array $config = [];
    protected array $defaults = [
        'enabled' => true,
        'cooldown_hours' => 24,
        'reset_on_miss' => true,
        'max_days' => 7,
        'timezone' => 'UTC',
        'theme' => 'default',
        'show_animations' => true,
        'custom_css' => '',
        'title_text' => '',
        'button_text' => '',
        'cooldown_text' => '',
    ];

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Load configuration from database
     */
    private function loadConfig(): void
    {
        $configs = DailyRewardConfig::findAll();
        
        foreach ($configs as $config) {
            $this->config[$config->key] = $config->value;
        }

        $this->config = array_merge($this->defaults, $this->config);
    }

    /**
     * Get config value
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Get all config
     */
    public function getAllConfig(): array
    {
        return $this->config;
    }

    /**
     * Set config value
     */
    public function setConfig(string $key, $value): void
    {
        $config = DailyRewardConfig::query()->where('key', $key)->fetchOne();

        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            $config = new DailyRewardConfig();
            $config->key = $key;
            $config->value = $value;
            $config->save();
        }

        $this->config[$key] = $value;
    }

    /**
     * Get all rewards
     */
    public function getRewards(bool $activeOnly = false)
    {
        $query = DailyReward::query()->orderBy('dayNumber', 'ASC');

        if ($activeOnly) {
            $query->where('isActive', true);
        }

        return $query->fetchAll();
    }

    /**
     * Get reward by day number
     */
    public function getRewardByDay(int $dayNumber): ?DailyReward
    {
        return DailyReward::query()
            ->where('dayNumber', $dayNumber)
            ->where('isActive', true)
            ->fetchOne();
    }

    /**
     * Save reward
     */
    public function saveReward(array $data): void
    {
        $reward = DailyReward::query()->where('dayNumber', $data['day_number'])->fetchOne();

        if ($reward) {
            $reward->dayNumber = $data['day_number'] ?? $reward->dayNumber;
            $reward->rewardType = $data['reward_type'] ?? $reward->rewardType;
            $reward->rewardValue = $data['reward_value'] ?? $reward->rewardValue;
            $reward->rewardData = $data['reward_data'] ?? $reward->rewardData;
            $reward->icon = $data['icon'] ?? $reward->icon;
            $reward->name = $data['name'] ?? $reward->name;
            $reward->isActive = $data['is_active'] ?? $reward->isActive;
            $reward->save();
        } else {
            $reward = new DailyReward();
            $reward->dayNumber = $data['day_number'] ?? 1;
            $reward->rewardType = $data['reward_type'] ?? 'coins';
            $reward->rewardValue = $data['reward_value'] ?? 0;
            $reward->rewardData = $data['reward_data'] ?? null;
            $reward->icon = $data['icon'] ?? null;
            $reward->name = $data['name'] ?? null;
            $reward->isActive = $data['is_active'] ?? true;
            $reward->save();
        }
    }

    /**
     * Delete reward
     */
    public function deleteReward(int $dayNumber): void
    {
        DailyReward::query()->where('dayNumber', $dayNumber)->delete();
    }

    /**
     * Get user progress
     */
    public function getUserProgress(int $userId): ?DailyRewardUser
    {
        $progress = DailyRewardUser::query()->where('userId', $userId)->fetchOne();

        if (!$progress) {
            $progress = new DailyRewardUser();
            $progress->userId = $userId;
            $progress->currentDay = 1;
            $progress->streak = 0;
            $progress->save();
        }

        return $progress;
    }

    /**
     * Check if user can claim reward
     */
    public function canClaim(int $userId): bool
    {
        $progress = $this->getUserProgress($userId);
        $cooldownHours = (int)$this->getConfig('cooldown_hours', 24);

        if (!$progress->lastClaim) {
            return true;
        }

        $lastClaim = new \DateTime($progress->lastClaim);
        $now = new \DateTime();

        $diff = $now->getTimestamp() - $lastClaim->getTimestamp();
        $hoursPassed = $diff / 3600;

        return $hoursPassed >= $cooldownHours;
    }

    /**
     * Get time until next claim
     */
    public function getTimeUntilNextClaim(int $userId): int
    {
        $progress = $this->getUserProgress($userId);
        $cooldownHours = (int)$this->getConfig('cooldown_hours', 24);

        if (!$progress->lastClaim) {
            return 0;
        }

        $lastClaim = new \DateTime($progress->lastClaim);
        $nextClaim = (clone $lastClaim)->modify("+{$cooldownHours} hours");
        $now = new \DateTime();

        if ($now >= $nextClaim) {
            return 0;
        }

        return $nextClaim->getTimestamp() - $now->getTimestamp();
    }

    /**
     * Claim reward
     */
    public function claimReward(int $userId): array
    {
        if (!$this->canClaim($userId)) {
            return ['success' => false, 'message' => __('dailyrewards.cooldown_not_expired')];
        }

        $progress = $this->getUserProgress($userId);
        $resetOnMiss = $this->getConfig('reset_on_miss', true);

        // Check if streak should be reset
        if ($progress->lastClaim && $resetOnMiss) {
            $lastClaim = new \DateTime($progress->lastClaim);
            $now = new \DateTime();
            $daysDiff = (int)$now->diff($lastClaim)->format('%a');

            if ($daysDiff > 1) {
                $progress->streak = 0;
                $progress->currentDay = 1;
                $progress->save();
            }
        }

        $currentDay = $progress->currentDay;
        $maxDays = (int)$this->getConfig('max_days', 7);

        // Loop back to day 1
        if ($currentDay > $maxDays) {
            $currentDay = 1;
        }

        $reward = $this->getRewardByDay($currentDay);

        if (!$reward) {
            return ['success' => false, 'message' => __('dailyrewards.reward_not_found')];
        }

        // Give reward
        $result = $this->giveReward($userId, $reward);

        if ($result['success']) {
            // Update progress
            $newDay = $currentDay + 1;
            if ($newDay > $maxDays) {
                $newDay = 1;
            }

            $progress->currentDay = $newDay;
            $progress->streak += 1;
            $progress->lastClaim = new \DateTime();
            $progress->totalClaimed += 1;
            $progress->save();

            // Log history
            $history = new DailyRewardHistory();
            $history->userId = $userId;
            $history->dayNumber = $currentDay;
            $history->rewardType = $reward->rewardType;
            $history->rewardValue = $reward->rewardValue;
            $history->claimedAt = new \DateTime();
            $history->save();

            // Fire event
            events()->dispatch('dailyrewards.claimed', [
                'user_id' => $userId,
                'day_number' => $currentDay,
                'reward' => $reward,
                'streak' => $progress->streak,
            ]);

            return [
                'success' => true,
                'message' => __('dailyrewards.reward_received'),
                'reward' => $reward,
                'new_day' => $newDay,
                'streak' => $progress->streak,
            ];
        }

        return $result;
    }

    /**
     * Give reward to user
     */
    private function giveReward(int $userId, DailyReward $reward): array
    {
        switch ($reward->rewardType) {
            case 'currency':
                $user = user();
                $user->balance += $reward->rewardValue;
                $user->save();

                return ['success' => true, 'type' => 'currency', 'amount' => $reward->rewardValue];

            case 'item':
                // Add item to user inventory
                return ['success' => true, 'type' => 'item'];

            case 'custom':
                // Fire custom event
                events()->dispatch('dailyrewards.custom', [
                    'user_id' => $userId,
                    'reward' => $reward,
                ]);
                return ['success' => true, 'type' => 'custom'];

            default:
                return ['success' => false, 'message' => __('dailyrewards.unknown_reward_type')];
        }
    }

    /**
     * Get user history
     */
    public function getUserHistory(int $userId, int $limit = 30): array
    {
        return DailyRewardHistory::query()
            ->where('userId', $userId)
            ->orderBy('claimedAt', 'DESC')
            ->limit($limit)
            ->fetchAll();
    }
}