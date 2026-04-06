<?php

namespace DailyRewards\Services;

use DailyRewards\Database\Entities\DailyRewardConfig;
use DailyRewards\Database\Entities\DailyReward;
use DailyRewards\Database\Entities\DailyRewardUser;
use DailyRewards\Database\Entities\DailyRewardHistory;

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
        $configs = DailyRewardConfig::all();
        
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
        $config = DailyRewardConfig::where('key', $key)->first();

        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            DailyRewardConfig::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        $this->config[$key] = $value;
    }

    /**
     * Get all rewards
     */
    public function getRewards(bool $activeOnly = false)
    {
        $query = DailyReward::orderBy('day_number', 'ASC');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->all();
    }

    /**
     * Get reward by day number
     */
    public function getRewardByDay(int $dayNumber): ?DailyReward
    {
        return DailyReward::where('day_number', $dayNumber)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Save reward
     */
    public function saveReward(array $data): void
    {
        $reward = DailyReward::where('day_number', $data['day_number'])->first();

        if ($reward) {
            $reward->update($data);
        } else {
            DailyReward::create($data);
        }
    }

    /**
     * Delete reward
     */
    public function deleteReward(int $dayNumber): void
    {
        DailyReward::where('day_number', $dayNumber)->delete();
    }

    /**
     * Get user progress
     */
    public function getUserProgress(int $userId): ?DailyRewardUser
    {
        $progress = DailyRewardUser::where('user_id', $userId)->first();

        if (!$progress) {
            $progress = DailyRewardUser::create([
                'user_id' => $userId,
                'current_day' => 1,
                'streak' => 0,
            ]);
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
            DailyRewardHistory::create([
                'user_id' => $userId,
                'day_number' => $currentDay,
                'reward_type' => $reward->rewardType,
                'reward_value' => $reward->rewardValue,
                'claimed_at' => new \DateTime(),
            ]);

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
        return DailyRewardHistory::where('user_id', $userId)
            ->orderBy('claimed_at', 'DESC')
            ->limit($limit)
            ->all();
    }
}