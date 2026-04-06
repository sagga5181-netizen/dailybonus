<?php

namespace Flute\Modules\DailyRewards\Providers;

use Flute\Core\ModulesManager\Contracts\ModuleExtensionInterface;
use Flute\Core\Database\Cycle\EntityHandler;
use Flute\Core\Router\Router;
use Flute\Core\Admin\AdminPanel;

class DailyRewardsServiceProvider implements ModuleExtensionInterface
{
    public function register(): void
    {
        // Database entities
        $this->loadEntities();

        // Configuration
        $this->loadConfigs();
    }

    public function boot(\DI\Container $container): void
    {
        // Load entities
        $this->loadEntities();

        // Load translations
        $this->loadTranslations();

        // Load SCSS styles
        $this->loadScss('Resources/assets/scss/daily-rewards.scss');

        // Load routes
        $this->loadRoutesFrom('Routes/web.php');

        // Load views
        $this->loadViews('Resources/views', 'dailyrewards');

        // Load widgets
        $this->loadWidgets();

        // Register admin menu
        $this->registerAdminMenu();
    }

    /**
     * Load database entities
     */
    private function loadEntities(): void
    {
        $entityHandler = app(EntityHandler::class);
        
        $entityHandler->addEntities([
            \Flute\Modules\DailyRewards\Database\Entities\DailyRewardConfig::class,
            \Flute\Modules\DailyRewards\Database\Entities\DailyReward::class,
            \Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser::class,
            \Flute\Modules\DailyRewards\Database\Entities\DailyRewardHistory::class,
        ]);
    }

    /**
     * Load translations
     */
    private function loadTranslations(): void
    {
        // Translations are loaded from src/Resources/lang/
    }

    /**
     * Register admin menu item
     */
    private function registerAdminMenu(): void
    {
        // Add menu item to admin panel
        $admin = app(AdminPanel::class);
        
        $admin->addMenuItem('dailyrewards', [
            'title' => 'Ежедневные награды',
            'icon' => 'gift',
            'route' => 'dailyrewards.admin.index',
            'permissions' => ['admin.boss', 'dailyrewards.manage'],
            'position' => 50,
        ]);
    }
}