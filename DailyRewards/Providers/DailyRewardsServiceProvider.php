<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        // Auto-load module resources
        $this->bootstrapModule();
        
        // Load views with namespace
        $this->loadViews('Resources/views', 'dailyrewards');
    }
}
