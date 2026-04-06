<div class="widget widget-daily-bonus" id="daily-bonus-widget">
    <div class="widget-header">
        <h3 class="widget-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Ежедневный бонус
            @if(isset($userBonus['cycleEnabled']) && $userBonus['cycleEnabled'] && isset($userBonus['cycleCount']))
                <span class="cycle-badge">Цикл {{ $userBonus['cycleCount'] }}</span>
            @endif
        </h3>
    </div>
    <div class="widget-body">
        @if(!$userBonus['isLoggedIn'])
            <div class="daily-bonus-guest">
                <p class="text-center text-muted">Войдите, чтобы получать ежедневные бонусы</p>
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    Войти
                </a>
            </div>
        @else
            @if($showStats ?? true)
            <div class="bonus-stats mb-4">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="stat-item">
                            <span class="stat-value">{{ $userBonus['claimCount'] ?? 0 }}</span>
                            <span class="stat-label">Дней</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-item">
                            <span class="stat-value">{{ $userBonus['currentDay'] ?? 1 }}</span>
                            <span class="stat-label">День</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-item">
                            <span class="stat-value">{{ number_format($userBonus['totalClaimed'] ?? 0, 0, ',', ' ') }}</span>
                            <span class="stat-label">Получено</span>
                        </div>
                    </div>
                </div>
                @if(isset($userBonus['progress']))
                <div class="progress-wrapper mt-3">
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $userBonus['progress'] }}%"></div>
                    </div>
                    <span class="progress-label">{{ $userBonus['progress'] }}% прогресса</span>
                </div>
                @endif
            </div>
            @endif
            <div class="bonus-days">
                <div class="days-grid">
                    @foreach($bonusDays as $index => $day)
                        @php
                            $isClaimed = isset($userBonus['claimCount']) && $day['day'] <= $userBonus['claimCount'];
                            $isCurrent = isset($userBonus['currentDay']) && $day['day'] == $userBonus['currentDay'] && $userBonus['canClaim'];
                            $islocked = isset($userBonus['currentDay']) && $day['day'] > $userBonus['currentDay'];
                        @endphp
                        <div class="bonus-day {{ $isClaimed ? 'claimed' : '' }} {{ $isCurrent ? 'current' : '' }} {{ $islocked ? 'locked' : '' }}">
                            <div class="day-number">День {{ $day['day'] }}</div>
                            <div class="day-amount">{{ number_format($day['amount'], 0, ',', ' ') }} {{ $rewardType === 'balance' ? '₽' : '' }}</div>
                            @if($isClaimed)
                                <div class="day-status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            @elseif($isCurrent)
                                <div class="day-status current-badge">Сегодня</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bonus-action mt-4">
                @if($userBonus['canClaim'])
                    <button type="button" class="btn btn-success btn-claim w-100" onclick="claimDailyBonus()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Получить бонус
                    </button>
                @else
                    @if($showTimer && isset($userBonus['nextClaimTime']))
                        <div class="countdown-wrapper text-center">
                            <p class="text-muted mb-2">Следующий бонус через:</p>
                            <div class="countdown-timer" data-time="{{ $userBonus['nextClaimTime'] }}">
                                <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                            </div>
                        </div>
                    @else
                        <button type="button" class="btn btn-secondary w-100" disabled>
                            Возвращайтесь завтра
                        </button>
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>
<style>
.widget-daily-bonus { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px; padding: 20px; color: #fff; }
.widget-daily-bonus .widget-title { display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #fff; flex-wrap: wrap; }
.widget-daily-bonus .cycle-badge { font-size: 11px; background: rgba(59, 130, 246, 0.3); color: #60a5fa; padding: 4px 10px; border-radius: 20px; font-weight: 500; }
.widget-daily-bonus .bonus-stats { background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 15px; }
.widget-daily-bonus .stat-item { display: flex; flex-direction: column; align-items: center; }
.widget-daily-bonus .stat-value { font-size: 20px; font-weight: 700; color: #ffd700; }
.widget-daily-bonus .stat-label { font-size: 11px; color: rgba(255, 255, 255, 0.6); margin-top: 2px; }
.widget-daily-bonus .progress-wrapper { text-align: center; }
.widget-daily-bonus .progress-bar-custom { height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; }
.widget-daily-bonus .progress-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 3px; transition: width 0.5s ease; }
.widget-daily-bonus .progress-label { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 6px; display: block; }
.widget-daily-bonus .days-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
.widget-daily-bonus .bonus-day { aspect-ratio: 1.2; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px; background: rgba(255, 255, 255, 0.08); position: relative; transition: all 0.3s ease; padding: 8px; }
.widget-daily-bonus .bonus-day.claimed { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.widget-daily-bonus .bonus-day.current { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); animation: pulse 2s infinite; }
.widget-daily-bonus .bonus-day.locked { opacity: 0.4; }
.widget-daily-bonus .day-number { font-size: 10px; font-weight: 600; color: rgba(255, 255, 255, 0.7); text-align: center; }
.widget-daily-bonus .bonus-day.claimed .day-number, .widget-daily-bonus .bonus-day.current .day-number { color: #fff; }
.widget-daily-bonus .day-amount { font-size: 12px; font-weight: 700; color: #ffd700; margin-top: 2px; }
.widget-daily-bonus .bonus-day.claimed .day-amount, .widget-daily-bonus .bonus-day.current .day-amount { color: #fff; }
.widget-daily-bonus .day-status { position: absolute; top: 4px; right: 4px; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #fff; color: #28a745; }
.widget-daily-bonus .current-badge { position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); width: auto; height: auto; border-radius: 4px; padding: 2px 6px; font-size: 8px; background: #ffc107; color: #000; font-weight: 600; }
.widget-daily-bonus .btn-claim { font-size: 15px; font-weight: 600; padding: 14px 24px; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border: none; }
.widget-daily-bonus .btn-claim:hover { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
.widget-daily-bonus .countdown-timer { font-size: 28px; font-weight: 700; color: #ffd700; font-family: monospace; }
.widget-daily-bonus .btn-secondary { background: rgba(255,255,255,0.1); border: none; color: rgba(255,255,255,0.5); padding: 14px; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.03); } }
@media (max-width: 480px) { .widget-daily-bonus .days-grid { grid-template-columns: repeat(4, 1fr); } }
</style>
<script>
function claimDailyBonus() {
    fetch('{{ route("dailybonus.claim") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { 
            showToast(data.message, 'success'); 
            setTimeout(() => location.reload(), 1500);
        } else { 
            showToast(data.message || 'Ошибка', 'error'); 
        }
    })
    .catch(error => { showToast('Произошла ошибка', 'error'); });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'daily-bonus-toast toast-' + type;
    toast.innerHTML = '<span>' + message + '</span>';
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:14px 20px;border-radius:10px;z-index:9999;font-weight:500;animation:slideIn 0.3s ease;';
    toast.style.background = type === 'success' ? '#22c55e' : '#ef4444';
    toast.style.color = '#fff';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

const style = document.createElement('style');
style.textContent = '@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }';
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function() {
    const timer = document.querySelector('.countdown-timer');
    if (timer) {
        let time = parseInt(timer.dataset.time);
        if (time > 0) {
            setInterval(function() {
                time--;
                if (time <= 0) { location.reload(); return; }
                const hours = Math.floor(time / 3600);
                const minutes = Math.floor((time % 3600) / 60);
                const seconds = time % 60;
                timer.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                timer.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                timer.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
            }, 1000);
        }
    }
});
</script>
