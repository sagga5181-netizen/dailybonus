<?php

use Flute\Routing\Route;

// API: Claim reward
Route::post('/api/dailyreward/claim', function() {
    $user = user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Not authorized']);
    }
    
    $data = request()->all();
    $day = $data['day'] ?? 1;
    
    // Get user progress
    $progress = \Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser::query()
        ->where('userId', $user->id)
        ->fetchOne();
    
    if (!$progress) {
        $progress = new \Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser();
        $progress->userId = $user->id;
        $progress->currentDay = 1;
        $progress->streak = 0;
    } else {
        // Check cooldown
        if ($progress->lastClaim) {
            $lastClaim = $progress->lastClaim instanceof \DateTimeImmutable 
                ? $progress->lastClaim 
                : new \DateTimeImmutable($progress->lastClaim);
            $now = new \DateTimeImmutable();
            $diffHours = ($now->getTimestamp() - $lastClaim->getTimestamp()) / 3600;
            
            if ($diffHours < 24) {
                return response()->json(['success' => false, 'message' => 'Wait for next day']);
            }
        }
        
        // Increment day
        $progress->currentDay = $progress->currentDay + 1;
        
        // Get total days
        $totalDays = \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()->count();
        if (!$totalDays) {
            $totalDays = 7;
        }
        
        if ($progress->currentDay > $totalDays) {
            $progress->currentDay = 1;
            $progress->streak = $progress->streak + 1;
        }
    }
    
    // Get reward for current day
    $reward = \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()
        ->where('dayNumber', $progress->currentDay)
        ->where('isActive', true)
        ->fetchOne();
    
    if ($reward && $reward->balance > 0) {
        // Add balance to user (implement user balance system)
        // For now just log
        // user()->addBalance($reward->balance);
    }
    
    $progress->lastClaim = new \DateTimeImmutable();
    $progress->save();
    
    return response()->json([
        'success' => true, 
        'day' => $progress->currentDay,
        'balance' => $reward ? $reward->balance : 0
    ]);
})->name('api.dailyreward.claim');
