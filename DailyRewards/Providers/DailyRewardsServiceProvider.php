<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\Support\ModuleServiceProvider;

class DailyRewardsServiceProvider extends ModuleServiceProvider
{
    /**
     * Load entities, configs, views, translations, SCSS, routes, widgets
     */
    public function boot(\DI\Container $container): void
    {
        // Only DB entities
        $this->loadEntities();

        // Only configuration
        $this->loadConfigs();

        // Only translations
        $this->loadTranslations();

        // Load SCSS styles
        $this->loadScss('Resources/assets/scss/daily-rewards.scss');

        // Load routes
        require $this->getModulePath('Routes/web.php');

        // Load views
        $this->loadViews('Resources/views', 'dailyrewards');

        // Load widgets
        $this->loadWidgets();
    }
}