<?php

namespace Flute\Modules\DailyBonus\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyBonusProvider extends ModuleServiceProvider
{
    protected ?string $moduleName = 'DailyBonus';

    public function boot(\DI\Container $container): void
    {
        $this->bootstrapModule();
    }
}