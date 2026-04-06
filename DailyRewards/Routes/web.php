<?php

use Flute\Core\Router\Router;

return function (Router $router) {
    // Admin routes
    $router->screen('/admin/daily-rewards', '\DailyRewards\Admin\Screens\DailyRewardsScreen', 'dailyrewards.admin.index');

    // API routes
    $router->post('/api/daily-rewards/claim', '\DailyRewards\Http\Controllers\DailyRewardsController@claim');
    $router->get('/api/daily-rewards/status', '\DailyRewards\Http\Controllers\DailyRewardsController@status');
    $router->get('/api/daily-rewards/history', '\DailyRewards\Http\Controllers\DailyRewardsController@history');
    $router->get('/api/daily-rewards/can-claim', '\DailyRewards\Http\Controllers\DailyRewardsController@canClaim');
};