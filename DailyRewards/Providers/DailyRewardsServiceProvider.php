<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Core\Admin\AdminPanel;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    public function register(\DI\Container $container): void
    {
        // Parent class automatically loads entities from database/Entities
    }

    public function boot(\DI\Container $container): void
    {
        // Load SCSS styles
        $this->loadScss('Resources/assets/scss/daily-rewards.scss');

        // Load routes - use absolute path from module directory
        require $this->getModulePath('Routes/web.php');

        // Load views
        $this->loadViews('Resources/views', 'dailyrewards');

        // Load widgets
        $this->loadWidgets();

        // Register admin menu
        $admin = app(AdminPanel::class);
        
        $admin->addMenuItem('dailyrewards', [
            'title' => 'Ежедневные награды',
            'icon' => 'gift',
            'route' => 'dailyrewards.admin.index',
            'permissions' => ['admin.boss', 'dailyrewards.manage'],
            'position' => 50,
        ]);
    }
}