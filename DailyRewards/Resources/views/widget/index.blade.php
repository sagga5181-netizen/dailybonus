<div class="daily-reward-widget">
    <h3>{{ $title }}</h3>
    <p>День: {{ $currentDay }}</p>
    
    @if($showStreak)
    <p>Стрик: {{ $streak }}</p>
    @endif
    
    @if($canClaim)
        <button class="btn-claim">Получить бонус</button>
    @else
        <button disabled>Получено</button>
    @endif
</div>

<style>
.daily-reward-widget {
    padding: 15px;
    background: #2a2a3e;
    border-radius: 8px;
    color: white;
}
.daily-reward-widget h3 {
    margin-top: 0;
}
.btn-claim {
    background: #4ade80;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}
</style>
