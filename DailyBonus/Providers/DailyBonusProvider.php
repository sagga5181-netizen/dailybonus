<?php

namespace Flute\Modules\DailyBonus\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyBonusProvider extends ModuleServiceProvider
{
    protected ?string $moduleName = 'DailyBonus';

    public function boot(\DI\Container $container): void
    {
        $this->registerWidgets();
        $this->bootstrapModule();
    }

    public function registerWidgets(array $widgets): array
    {
        $widgets['daily_bonus'] = \Flute\Modules\DailyBonus\Widgets\DailyBonusWidget::class;
        return $widgets;
    }
}