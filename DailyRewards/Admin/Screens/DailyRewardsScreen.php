<?php

namespace Flute\Modules\DailyRewards\Admin\Screens;

use Flute\Core\Admin\Abstractions\AbstractScreen;
use Flute\Core\Http\Request;

class DailyRewardsScreen extends AbstractScreen
{
    public function render(): string
    {
        $tab = request()->get('tab', 'general');

        return view('dailyrewards::admin.index', [
            'tab' => $tab,
            'module' => $this->module,
        ]);
    }

    /**
     * Handle form submissions
     */
    public function saveSettings(Request $request): void
    {
        $tab = $request->get('tab', 'general');
        $data = $request->all();

        switch ($tab) {
            case 'general':
                $this->saveGeneralSettings($data);
                break;
            case 'rewards':
                $this->saveRewardsSettings($data);
                break;
            case 'visual':
                $this->saveVisualSettings($data);
                break;
            case 'integration':
                $this->saveIntegrationSettings($data);
                break;
        }

        back()->with('success', __('dailyrewards.settings_saved'));
    }

    private function saveGeneralSettings(array $data): void
    {
        $config = [
            'enabled' => isset($data['enabled']) ? 1 : 0,
            'cooldown_hours' => (int)($data['cooldown_hours'] ?? 24),
            'timezone' => $data['timezone'] ?? 'UTC',
            'reset_on_miss' => isset($data['reset_on_miss']) ? 1 : 0,
            'max_days' => (int)($data['max_days'] ?? 7),
        ];

        foreach ($config as $key => $value) {
            $this->module->setConfig($key, $value);
        }
    }

    private function saveVisualSettings(array $data): void
    {
        $config = [
            'theme' => $data['theme'] ?? 'default',
            'show_animations' => isset($data['show_animations']) ? 1 : 0,
            'title_text' => $data['title_text'] ?? '',
            'button_text' => $data['button_text'] ?? '',
            'cooldown_text' => $data['cooldown_text'] ?? '',
            'custom_css' => $data['custom_css'] ?? '',
        ];

        foreach ($config as $key => $value) {
            $this->module->setConfig($key, $value);
        }
    }

    private function saveIntegrationSettings(array $data): void
    {
        $config = [
            'api_key' => $data['api_key'] ?? '',
            'webhook_url' => $data['webhook_url'] ?? '',
        ];

        foreach ($config as $key => $value) {
            $this->module->setConfig($key, $value);
        }
    }

    private function saveRewardsSettings(array $data): void
    {
        // Save individual rewards
        if (isset($data['rewards'])) {
            foreach ($data['rewards'] as $dayNumber => $rewardData) {
                $this->module->saveReward([
                    'day_number' => $dayNumber,
                    ...$rewardData,
                ]);
            }
        }
    }
}