<?php

use Flute\Core\Router\Router;

return function (Router $router) {
    // Admin routes
    $router->screen('/admin/daily-rewards', '\Flute\Modules\DailyRewards\Admin\Screens\DailyRewardsScreen', 'dailyrewards.admin.index');

    // API routes
    $router->post('/api/daily-rewards/claim', '\Flute\Modules\DailyRewards\Http\Controllers\DailyRewardsController@claim');
    $router->get('/api/daily-rewards/status', '\Flute\Modules\DailyRewards\Http\Controllers\DailyRewardsController@status');
    $router->get('/api/daily-rewards/history', '\Flute\Modules\DailyRewards\Http\Controllers\DailyRewardsController@history');
    $router->get('/api/daily-rewards/can-claim', '\Flute\Modules\DailyRewards\Http\Controllers\DailyRewardsController@canClaim');
};