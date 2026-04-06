<?php

use Flute\Routing\Route;

// Simple claim API
Route::post('/api/dailyreward/claim', function() {
    $user = user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Not authorized']);
    }
    
    $userId = $user->id;
    
    // Get user progress
    $progress = \Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser::query()
        ->where('userId', $userId)
        ->fetchOne();
    
    if (!$progress) {
        $progress = new \Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser();
        $progress->userId = $userId;
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
        if ($progress->currentDay > 7) {
            $progress->currentDay = 1;
            $progress->streak = $progress->streak + 1;
        }
    }
    
    $progress->lastClaim = new \DateTimeImmutable();
    $progress->save();
    
    return response()->json(['success' => true, 'day' => $progress->currentDay]);
});
