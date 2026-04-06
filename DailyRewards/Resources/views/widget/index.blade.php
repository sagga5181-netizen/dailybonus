<div class="daily-reward-widget">
    <div class="dr-header">
        <h3><i class="fas fa-gift"></i> Ежедневные бонусы</h3>
    </div>
    
    <div class="dr-content">
        <div class="dr-streak">
            <span class="dr-label">День:</span>
            <span class="dr-value">{{ $currentDay }}</span>
        </div>
        
        <div class="dr-status">
            @if($canClaim)
                <button class="dr-btn dr-btn-claim" onclick="claimDailyReward()">
                    Получить бонус
                </button>
            @else
                <button class="dr-btn dr-btn-disabled" disabled>
                    Получено
                </button>
            @endif
        </div>
    </div>
</div>

<style>
.daily-reward-widget {
    background: #1e1e2e;
    border-radius: 12px;
    padding: 20px;
    color: #fff;
}
.dr-header h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
}
.dr-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dr-streak {
    font-size: 16px;
}
.dr-value {
    font-weight: bold;
    color: #ffd700;
    margin-left: 8px;
}
.dr-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
}
.dr-btn-claim {
    background: #4ade80;
    color: #000;
}
.dr-btn-claim:hover {
    background: #22c55e;
}
.dr-btn-disabled {
    background: #3f3f46;
    color: #71717a;
    cursor: not-allowed;
}
</style>
