<?php

use Flute\Routing\Route;
use Flute\Modules\DailyRewards\Database\Entities\DailyReward;

// Admin: List rewards
Route::get('/admin/dailyrewards', function() {
    $rewards = DailyReward::query()->orderBy('dayNumber', 'ASC')->fetchAll();
    return view('dailyrewards_admin::index', ['rewards' => $rewards]);
})->name('admin.dailyrewards.index');

// Admin: Save reward
Route::post('/admin/dailyrewards/save', function() {
    $data = request()->all();
    
    if (!empty($data['id'])) {
        $reward = DailyReward::query()->where('id', $data['id'])->fetchOne();
        if ($reward) {
            $reward->dayNumber = $data['dayNumber'];
            $reward->image = $data['image'] ?? null;
            $reward->balance = $data['balance'];
            $reward->save();
        }
    } else {
        $reward = new DailyReward();
        $reward->dayNumber = $data['dayNumber'];
        $reward->image = $data['image'] ?? null;
        $reward->balance = $data['balance'];
        $reward->isActive = true;
        $reward->save();
    }

    return redirect()->route('admin.dailyrewards.index');
})->name('admin.dailyrewards.save');

// Admin: Delete reward
Route::get('/admin/dailyrewards/delete/{id}', function(int $id) {
    $reward = DailyReward::query()->where('id', $id)->fetchOne();
    if ($reward) {
        $reward->delete();
    }
    return redirect()->route('admin.dailyrewards.index');
})->name('admin.dailyrewards.delete');
