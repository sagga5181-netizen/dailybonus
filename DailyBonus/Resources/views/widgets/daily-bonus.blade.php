<div class="widget widget-daily-bonus" id="daily-bonus-widget">
    <div class="widget-header">
        <h3 class="widget-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            {{ __('dailybonus.widget.title') }}
        </h3>
    </div>
    <div class="widget-body">
        @if(!$userBonus['isLoggedIn'])
            <div class="daily-bonus-guest">
                <p class="text-center text-muted">{{ __('dailybonus.widget.login_required') }}</p>
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    {{ __('dailybonus.widget.login') }}
                </a>
            </div>
        @else
            <div class="bonus-stats mb-4">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stat-item">
                            <span class="stat-value">{{ $userBonus['claimCount'] ?? 0 }}</span>
                            <span class="stat-label">{{ __('dailybonus.stats.days_claimed') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <span class="stat-value">{{ number_format($userBonus['totalClaimed'] ?? 0, 0, ',', ' ') }}</span>
                            <span class="stat-label">{{ __('dailybonus.stats.total_received') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bonus-days">
                <div class="days-grid">
                    @foreach($bonusDays as $index => $day)
                        @php
                            $isClaimed = isset($userBonus['currentDay']) && $day['day'] < $userBonus['currentDay'];
                            $isCurrent = isset($userBonus['currentDay']) && $day['day'] == $userBonus['currentDay'] && $userBonus['canClaim'];
                            $islocked = isset($userBonus['currentDay']) && $day['day'] > $userBonus['currentDay'];
                        @endphp
                        <div class="bonus-day {{ $isClaimed ? 'claimed' : '' }} {{ $isCurrent ? 'current' : '' }} {{ $islocked ? 'locked' : '' }}">
                            <div class="day-number">{{ $day['day'] }}</div>
                            <div class="day-amount">{{ number_format($day['amount'], 0, ',', ' ') }}</div>
                            @if($isClaimed)
                                <div class="day-status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            @elseif($isCurrent)
                                <div class="day-status current-badge">
                                    {{ __('dailybonus.today') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bonus-action mt-4">
                @if($userBonus['canClaim'])
                    <button type="button" class="btn btn-success btn-claim w-100" onclick="claimDailyBonus()">
                        {{ __('dailybonus.claim') }}
                    </button>
                @else
                    @if($showTimer && isset($userBonus['nextClaimTime']))
                        <div class="countdown-wrapper text-center">
                            <p class="text-muted mb-2">{{ __('dailybonus.next_bonus_in') }}</p>
                            <div class="countdown-timer" data-time="{{ $userBonus['nextClaimTime'] }}">
                                <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                            </div>
                        </div>
                    @else
                        <button type="button" class="btn btn-secondary w-100" disabled>
                            {{ __('dailybonus.come_back_tomorrow') }}
                        </button>
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>
<style>
.widget-daily-bonus { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px; padding: 20px; color: #fff; }
.widget-daily-bonus .widget-title { display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #fff; }
.widget-daily-bonus .bonus-stats { background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 15px; }
.widget-daily-bonus .stat-item { display: flex; flex-direction: column; align-items: center; }
.widget-daily-bonus .stat-value { font-size: 24px; font-weight: 700; color: #ffd700; }
.widget-daily-bonus .stat-label { font-size: 12px; color: rgba(255, 255, 255, 0.6); }
.widget-daily-bonus .days-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
.widget-daily-bonus .bonus-day { aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; background: rgba(255, 255, 255, 0.1); position: relative; transition: all 0.3s ease; }
.widget-daily-bonus .bonus-day.claimed { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.widget-daily-bonus .bonus-day.current { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); animation: pulse 2s infinite; }
.widget-daily-bonus .bonus-day.locked { opacity: 0.5; }
.widget-daily-bonus .day-number { font-size: 12px; font-weight: 600; color: rgba(255, 255, 255, 0.8); }
.widget-daily-bonus .bonus-day.claimed .day-number, .widget-daily-bonus .bonus-day.current .day-number { color: #fff; }
.widget-daily-bonus .day-amount { font-size: 10px; font-weight: 700; color: #ffd700; }
.widget-daily-bonus .bonus-day.claimed .day-amount, .widget-daily-bonus .bonus-day.current .day-amount { color: #fff; }
.widget-daily-bonus .day-status { position: absolute; top: -4px; right: -4px; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #28a745; color: #fff; }
.widget-daily-bonus .current-badge { position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: auto; height: auto; border-radius: 4px; padding: 2px 6px; font-size: 8px; background: #ffc107; color: #000; }
.widget-daily-bonus .btn-claim { font-size: 16px; font-weight: 600; padding: 12px 24px; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; }
.widget-daily-bonus .countdown-timer { font-size: 28px; font-weight: 700; color: #ffd700; font-family: monospace; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
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
        if (data.success) { showNotification(data.message, 'success'); location.reload(); }
        else { showNotification(data.message, 'error'); }
    })
    .catch(error => { showNotification('An error occurred', 'error'); });
}
function showNotification(message, type) { alert(message); }
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