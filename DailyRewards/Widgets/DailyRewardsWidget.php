<?php

namespace Flute\Modules\DailyRewards\Widgets;

use Flute\Modules\DailyRewards\Services\DailyRewardsService;
use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;

class DailyRewardsWidget implements WidgetInterface
{
    /**
     * Returns the unique name of the widget.
     */
    public function getName(): string
    {
        return 'dailyrewards';
    }

    /**
     * Returns the widget's icon.
     */
    public function getIcon(): string
    {
        return 'gift';
    }

    /**
     * Returns the widget's default settings.
     */
    public function getSettings(): array
    {
        return [
            'theme' => 'default',
        ];
    }

    /**
     * Renders the widget with the given settings.
     */
    public function render(array $settings): ?string
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
        $theme = $settings['theme'] ?? $service->getConfig('theme', 'default');

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

    /**
     * Renders the form for editing the widget's settings.
     */
    public function renderSettingsForm(array $settings): string|bool
    {
        return '<div class="form-group">
            <label>Тема</label>
            <select name="settings[theme]" class="form-control">
                <option value="default" ' . ($settings['theme'] ?? 'default' === 'default' ? 'selected' : '') . '>По умолчанию</option>
                <option value="dark" ' . ($settings['theme'] ?? '' === 'dark' ? 'selected' : '') . '>Тёмная</option>
                <option value="light" ' . ($settings['theme'] ?? '' === 'light' ? 'selected' : '') . '>Светлая</option>
            </select>
        </div>';
    }

    /**
     * Validates the widget's settings before saving.
     */
    public function validateSettings(array $input): true|array
    {
        return true;
    }

    /**
     * Saves the widget's settings.
     */
    public function saveSettings(array $input): array
    {
        return $input;
    }

    /**
     * Returns the default grid width of the widget.
     */
    public function getDefaultWidth(): int
    {
        return 12;
    }

    /**
     * Returns the minimum grid width of the widget.
     */
    public function getMinWidth(): int
    {
        return 3;
    }

    /**
     * Checks if the widget has a settings form.
     */
    public function hasSettings(): bool
    {
        return true;
    }

    /**
     * Returns the toolbar buttons for the widget.
     */
    public function getButtons(): array
    {
        return [];
    }

    /**
     * Handles a widget action.
     */
    public function handleAction(string $action, ?string $widgetId = null): array
    {
        return [];
    }

    /**
     * Returns the category of the widget.
     */
    public function getCategory(): string
    {
        return 'rewards';
    }
}