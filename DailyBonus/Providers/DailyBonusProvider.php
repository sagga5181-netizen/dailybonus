<?php

namespace Flute\Modules\DailyBonus\Providers;

use Flute\Core\Support\ModuleServiceProvider;
use Illuminate\Support\Facades\View;

class DailyBonusProvider extends ModuleServiceProvider
{
    protected ?string $moduleName = 'DailyBonus';

    public function boot(\DI\Container $container): void
    {
        // Register view namespace for the module
        $modulePath = base_path('app/Modules/DailyBonus/Resources/views');
        View()->addNamespace('dailybonus', $modulePath);
        
        $this->bootstrapModule();
    }
}