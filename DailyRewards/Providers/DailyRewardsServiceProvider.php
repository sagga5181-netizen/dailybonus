<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    public function boot(\DI\Container $container): void
    {
        // Only DB entities
        $this->loadEntities();

        // Load routes
        require $this->getModulePath('Routes/web.php');

        // Load widget
        $this->loadWidgets();
    }
}
