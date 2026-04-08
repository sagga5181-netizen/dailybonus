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

        // Load API routes
        $apiPath = dirname(__DIR__, 2) . '/Routes/api.php';
        if (file_exists($apiPath)) {
            require_once $apiPath;
        }

        // Load admin routes
        $adminPath = dirname(__DIR__, 2) . '/Routes/admin.php';
        if (file_exists($adminPath)) {
            require_once $adminPath;
        }
    }
}
