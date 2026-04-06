<?php

namespace DailyRewards\Http\Controllers;

use Flute\Core\Http\Controller;
use DailyRewards\Services\DailyRewardsService;

class DailyRewardsController extends Controller
{
    protected DailyRewardsService $service;

    public function __construct()
    {
        $this->service = new DailyRewardsService();
    }

    /**
     * Claim reward
     */
    public function claim()
    {
        $userId = user()->id ?? null;

        if (!$userId) {
            return json(['success' => false, 'message' => 'Not authorized'], 401);
        }

        $result = $this->service->claimReward($userId);

        return json($result);
    }

    /**
     * Get status
     */
    public function status()
    {
        $userId = user()->id ?? null;

        if (!$userId) {
            return json(['success' => false, 'message' => 'Not authorized'], 401);
        }

        $progress = $this->service->getUserProgress($userId);
        $rewards = $this->service->getRewards();
        $canClaim = $this->service->canClaim($userId);
        $timeUntil = $this->service->getTimeUntilNextClaim($userId);

        return json([
            'success' => true,
            'progress' => $progress,
            'rewards' => $rewards,
            'can_claim' => $canClaim,
            'time_until' => $timeUntil,
        ]);
    }

    /**
     * Get history
     */
    public function history()
    {
        $userId = user()->id ?? null;
        $limit = request()->get('limit', 30);

        if (!$userId) {
            return json(['success' => false, 'message' => 'Not authorized'], 401);
        }

        $history = $this->service->getUserHistory($userId, $limit);

        return json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Check if can claim
     */
    public function canClaim()
    {
        $userId = user()->id ?? null;

        if (!$userId) {
            return json(['success' => false, 'message' => 'Not authorized'], 401);
        }

        $canClaim = $this->service->canClaim($userId);
        $timeUntil = $this->service->getTimeUntilNextClaim($userId);

        return json([
            'success' => true,
            'can_claim' => $canClaim,
            'time_until' => $timeUntil,
        ]);
    }
}