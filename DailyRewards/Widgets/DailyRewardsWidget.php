<?php

namespace Flute\Modules\DailyRewards\Widgets;

use Flute\Modules\DailyRewards\Services\DailyRewardsService;
use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;

class DailyRewardsWidget implements WidgetInterface
{
    /**
     * Render widget
     */
    public function render(): string
    {
        $user = user();
        
        if (!$user) {
            return '';
        }

        $service = new DailyRewardsService();

        // Check if module is enabled
        if (!$service->getConfig('enabled')) {
            return '';
        }

        $config = $service->getAllConfig();
        $rewards = $service->getRewards(true);
        $progress = $service->getUserProgress($user->id);
        $canClaim = $service->canClaim($user->id);
        $timeUntil = $service->getTimeUntilNextClaim($user->id);
        
        $maxDays = (int)$service->getConfig('max_days', 7);
        $theme = $service->getConfig('theme', 'default');

        return view('dailyrewards::widget.index', [
            'config' => $config,
            'rewards' => $rewards,
            'progress' => $progress,
            'canClaim' => $canClaim,
            'timeUntil' => $timeUntil,
            'maxDays' => $maxDays,
            'theme' => $theme,
            'userId' => $user->id,
        ]);
    }
}