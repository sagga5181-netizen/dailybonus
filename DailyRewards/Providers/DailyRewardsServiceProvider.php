<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        // Load entities
        $this->loadEntities();

        // Load views with namespace
        $this->loadViews('Resources/views', 'dailyrewards');

        // Load widgets
        $this->loadWidgets();

        // Load API routes - use getModulePath()
        $this->loadRoutesFrom($this->getModulePath() . 'Routes/api.php');

        // Load admin routes
        $this->loadRoutesFrom($this->getModulePath() . 'Routes/admin.php');
    }
}
