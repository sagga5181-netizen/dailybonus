<?php

namespace Flute\Modules\DailyBonus\Providers;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Core\Modules\Page\Services\WidgetManager;

class DailyBonusProvider extends ModuleServiceProvider
{
    protected ?string $moduleName = 'DailyBonus';

    public function boot(\DI\Container $container): void
    {
        $this->registerWidgets();
        $this->bootstrapModule();
    }

    protected function registerWidgets(): void
    {
        $widgetManager = app(WidgetManager::class);
        $widgetManager->registerWidget('daily_bonus', \Flute\Modules\DailyBonus\Widgets\DailyBonusWidget::class);
    }
}