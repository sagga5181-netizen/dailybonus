<?php

namespace Flute\Modules\DailyRewards\Widgets;

use Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser;
use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;

class DailyRewardsWidget implements WidgetInterface
{
    public function getName(): string
    {
        return 'dailyreward';
    }

    public function getIcon(): string
    {
        return 'gift';
    }

    public function getSettings(): array
    {
        return [];
    }

    public function render(array $settings): ?string
    {
        $user = user();
        if (!$user) {
            return '';
        }

        // Get user progress
        $progress = DailyRewardUser::query()->where('userId', $user->id)->fetchOne();

        // Create progress if not exists
        if (!$progress) {
            $progress = new DailyRewardUser();
            $progress->userId = $user->id;
            $progress->currentDay = 1;
            $progress->streak = 0;
            $progress->save();
        }

        // Check if can claim
        $canClaim = false;
        $cooldownHours = 24;
        
        if ($progress->lastClaim) {
            $lastClaim = $progress->lastClaim instanceof \DateTimeImmutable 
                ? $progress->lastClaim 
                : new \DateTimeImmutable($progress->lastClaim);
            $now = new \DateTimeImmutable();
            $diffHours = ($now->getTimestamp() - $lastClaim->getTimestamp()) / 3600;
            $canClaim = $diffHours >= $cooldownHours;
        } else {
            $canClaim = true;
        }

        $currentDay = $progress->currentDay;
        $streak = $progress->streak;

        return view('dailyrewards::widget.index', [
            'currentDay' => $currentDay,
            'streak' => $streak,
            'canClaim' => $canClaim,
        ]);
    }

    public function renderSettingsForm(array $settings): string
    {
        return '';
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
        return 12;
    }

    public function getMinWidth(): int
    {
        return 6;
    }

    public function hasSettings(): bool
    {
        return false;
    }

    public function getButtons(): array
    {
        return [];
    }

    public function handleAction(string $action, ?string $widgetId = null): array
    {
        return [];
    }

    public function getCategory(): string
    {
        return 'rewards';
    }
}