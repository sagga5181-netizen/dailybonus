<?php

namespace Flute\Modules\DailyRewards\Admin\Package;

use Flute\Core\Admin\Modules\AbstractAdminPackage;

class DailyRewardsAdminPackage extends AbstractAdminPackage
{
    public function initialize(): void
    {
        $this->loadViews('Resources/views/admin', 'dailyrewards_admin');
        $this->registerScss('Resources/assets/scss/admin.scss');
    }

    public function boot(): void
    {
        // Additional logic
    }

    public function getPermissions(): array
    {
        return ['dailyrewards.manage'];
    }

    public function getMenuItems(): array
    {
        return [
            [
                'title' => 'Ежедневные бонусы',
                'icon' => 'gift',
                'route' => route('admin.dailyrewards.index'),
            ]
        ];
    }

    public function getPriority(): int
    {
        return 50;
    }
}
