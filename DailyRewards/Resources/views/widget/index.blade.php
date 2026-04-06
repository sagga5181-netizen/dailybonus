<div class="daily-reward-widget">
    <h3>{{ $title }}</h3>
    
    <div class="dr-progress">
        <div class="dr-current-day">День {{ $currentDay }}</div>
        
        <div class="dr-rewards">
            @foreach($rewards as $reward)
                <div class="dr-reward {{ $reward->dayNumber == $currentDay ? 'current' : '' }} {{ $reward->dayNumber < $currentDay ? 'completed' : '' }}">
                    <div class="dr-reward-image">
                        @if($reward->image)
                            <img src="{{ $reward->image }}" alt="День {{ $reward->dayNumber }}">
                        @else
                            <div class="dr-reward-placeholder">?</div>
                        @endif
                    </div>
                    <div class="dr-reward-info">
                        <span class="dr-day">День {{ $reward->dayNumber }}</span>
                        <span class="dr-balance">{{ number_format($reward->balance, 2) }} ₽</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($showStreak)
        <div class="dr-streak">Стрик: {{ $streak }}</div>
        @endif
    </div>
    
    <div class="dr-claim">
        @if($canClaim && $currentReward)
            <button class="btn-claim" onclick="claimDailyReward({{ $currentDay }})">
                Получить {{ number_format($currentReward->balance, 2) }} ₽
            </button>
        @else
            <button class="btn-claim btn-claimed" disabled>
                @if($canClaim)
                    Нет награды
                @else
                    Получено
                @endif
            </button>
        @endif
    </div>
</div>

<style>
.daily-reward-widget {
    padding: 20px;
    background: #1e1e2e;
    border-radius: 12px;
    color: white;
}
.daily-reward-widget h3 {
    margin: 0 0 15px 0;
    text-align: center;
}
.dr-progress {
    margin-bottom: 15px;
}
.dr-current-day {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #ffd700;
}
.dr-rewards {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}
.dr-reward {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px;
    border-radius: 8px;
    background: #2a2a3e;
    opacity: 0.6;
    transition: all 0.3s;
}
.dr-reward.current {
    opacity: 1;
    border: 2px solid #4ade80;
    transform: scale(1.1);
}
.dr-reward.completed {
    opacity: 0.8;
    border: 2px solid #22c55e;
}
.dr-reward-image {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 5px;
}
.dr-reward-image img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 4px;
}
.dr-reward-placeholder {
    width: 40px;
    height: 40px;
    background: #3f3f46;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.dr-reward-info {
    text-align: center;
}
.dr-day {
    display: block;
    font-size: 12px;
    color: #9ca3af;
}
.dr-balance {
    display: block;
    font-size: 14px;
    font-weight: bold;
    color: #4ade80;
}
.dr-streak {
    text-align: center;
    margin-top: 10px;
    color: #ffd700;
}
.dr-claim {
    text-align: center;
}
.btn-claim {
    background: #4ade80;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    color: #000;
    transition: all 0.3s;
}
.btn-claim:hover {
    background: #22c55e;
    transform: scale(1.05);
}
.btn-claimed {
    background: #3f3f46;
    color: #71717a;
    cursor: not-allowed;
}
</style>

<script>
function claimDailyReward(day) {
    fetch('{{ route("api.dailyreward.claim") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ day: day })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Ошибка');
        }
    });
}
</script>
