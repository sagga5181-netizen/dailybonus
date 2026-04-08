<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        $modulePath = dirname(__DIR__);

        // Load entities
        $this->loadEntities();

        // Load views with namespace
        $this->loadViews($modulePath . '/Resources/views', 'dailyrewards');

        // Load widgets
        $this->loadWidgets();

        // Load API routes
        if (file_exists($modulePath . '/Routes/api.php')) {
            $this->loadRoutesFrom($modulePath . '/Routes/api.php');
        }

        // Load admin routes
        if (file_exists($modulePath . '/Routes/admin.php')) {
            $this->loadRoutesFrom($modulePath . '/Routes/admin.php');
        }
    }
}
