<!-- Daily Rewards Widget -->
<div class="daily-rewards-widget" data-theme="{{ $theme }}">
    <div class="dr-widget-wrapper">
        <!-- Header -->
        <div class="dr-widget-header">
            <h2 class="dr-widget-title">{{ $config['title_text'] ?: __('dailyrewards.widget.title') }}</h2>
            <p class="dr-widget-subtitle">{{ __('dailyrewards.widget.subtitle') }}</p>
            
            @if($progress && $progress->streak > 0)
            <div class="dr-streak-badge">
                <span class="dr-streak-icon">🔥</span>
                <span>{{ __('dailyrewards.widget.streak') }}: <strong>{{ $progress->streak }}</strong></span>
            </div>
            @endif
        </div>

        <!-- Days Grid -->
        <div class="dr-days-grid">
            @for($i = 1; $i <= $maxDays; $i++)
                @php
                    $reward = null;
                    foreach($rewards as $r) {
                        if($r->dayNumber == $i) { $reward = $r; break; }
                    }
                    
                    $isCompleted = $i < $progress->currentDay;
                    $isCurrent = $i === $progress->currentDay;
                    
                    $cardClass = 'dr-day-card';
                    if($isCompleted) $cardClass .= ' completed';
                    elseif($isCurrent) $cardClass .= ' current';
                    else $cardClass .= ' locked';
                @endphp
                <div class="{{ $cardClass }}" data-day="{{ $i }}">
                    <div class="dr-day-number">{{ __('dailyrewards.widget.day') }} {{ $i }}</div>
                    
                    @if($reward)
                    <div class="dr-day-icon">
                        <i class="fas fa-{{ $reward->icon ?? 'gift' }}"></i>
                    </div>
                    <div class="dr-day-reward">
                        @if($reward->rewardType === 'currency')
                            {{ number_format($reward->rewardValue) }}
                        @elseif($reward->rewardType === 'item')
                            ×{{ $reward->rewardData['quantity'] ?? 1 }}
                        @else
                            ★
                        @endif
                    </div>
                    <div class="dr-day-type">
                        {{ __('dailyrewards.rewards.type_' . $reward->rewardType) }}
                    </div>
                    @else
                    <div class="dr-day-icon">
                        <i class="fas fa-question"></i>
                    </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Progress Bar -->
        <div class="dr-progress-container">
            <div class="dr-progress-wrapper">
                <div class="dr-progress-bar" style="width: {{ (($progress->currentDay - 1) / $maxDays) * 100 }}%"></div>
            </div>
            <div class="dr-progress-text">
                <span>{{ $progress->currentDay }}/{{ $maxDays }} {{ __('dailyrewards.widget.days') }}</span>
                <span>{{ $progress->totalClaimed }} {{ __('dailyrewards.widget.total_claimed') }}</span>
            </div>
        </div>

        <!-- Claim Button -->
        <div class="dr-claim-section">
            @if($canClaim)
            <button type="button" class="dr-claim-btn" onclick="claimReward()">
                <i class="fas fa-gift"></i>
                <span>{{ $config['button_text'] ?: __('dailyrewards.widget.claim') }}</span>
            </button>
            @else
            <button type="button" class="dr-claim-btn disabled" disabled>
                <i class="fas fa-clock"></i>
                <span>{{ __('dailyrewards.widget.claimed') }}</span>
            </button>
            <div class="dr-cooldown" id="dr-cooldown" style="display: {{ $timeUntil > 0 ? 'flex' : 'none' }}">
                <span class="dr-cooldown-icon">⏱️</span>
                <span>{{ $config['cooldown_text'] ?: __('dailyrewards.widget.next_reward') }}:</span>
                <span class="dr-cooldown-time" id="dr-cooldown-time">{{ formatCooldown($timeUntil) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Success Modal -->
    <div id="dr-claim-success" class="dr-modal">
        <div class="dr-modal-content">
            <div class="dr-modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>{{ __('dailyrewards.widget.congrats') }}</h3>
            <p>{{ __('dailyrewards.widget.reward_received') }}</p>
        </div>
    </div>
</div>

<script>
window.dailyRewardsConfig = {
    userId: {{ $userId }},
    cooldownHours: {{ $config['cooldown_hours'] ?? 24 }},
    maxDays: {{ $maxDays }},
    lastClaim: {{ $progress->lastClaim ? "'{$progress->lastClaim}'" : 'null' }},
    canClaim: {{ $canClaim ? 'true' : 'false' }}
};

document.addEventListener('DOMContentLoaded', function() {
    initDailyRewards();
});

function initDailyRewards() {
    const config = window.dailyRewardsConfig || {};
    
    if (config.canClaim) {
        // Enable claim button - already done in HTML
    } else if (config.lastClaim) {
        startCooldownTimer();
    }
}

function claimReward() {
    const config = window.dailyRewardsConfig || {};
    const btn = document.querySelector('.dr-claim-btn');
    
    if (!btn || btn.classList.contains('disabled')) return;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    fetch('/api/daily-rewards/claim', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ user_id: config.userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data);
            updateUI(data);
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-gift"></i> {{ __("dailyrewards.widget.claim") }}';
        }
    })
    .catch(error => {
        console.error('Daily Rewards Error:', error);
        alert('An error occurred');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-gift"></i> {{ __("dailyrewards.widget.claim") }}';
    });
}

function showSuccessModal(data) {
    const modal = document.getElementById('dr-claim-success');
    if (modal) {
        modal.classList.add('active');
        setTimeout(() => {
            modal.classList.remove('active');
        }, 3000);
    }
}

function updateUI(data) {
    const streakEl = document.querySelector('.dr-streak-badge strong');
    if (streakEl && data.streak !== undefined) {
        streakEl.textContent = data.streak;
    }
    
    const progressBar = document.querySelector('.dr-progress-bar');
    if (progressBar && data.new_day) {
        const maxDays = window.dailyRewardsConfig?.maxDays || 7;
        const progress = ((data.new_day - 1) / maxDays) * 100;
        progressBar.style.width = progress + '%';
    }
    
    const btn = document.querySelector('.dr-claim-btn');
    if (btn) {
        btn.classList.add('disabled');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-check"></i> {{ __("dailyrewards.widget.claimed") }}';
    }
    
    startCooldownTimer();
}

function startCooldownTimer() {
    const config = window.dailyRewardsConfig || {};
    const cooldownEl = document.getElementById('dr-cooldown');
    const timeEl = document.getElementById('dr-cooldown-time');
    const btn = document.querySelector('.dr-claim-btn');
    
    if (!config.lastClaim || !timeEl) return;
    
    if (cooldownEl) {
        cooldownEl.style.display = 'flex';
    }
    
    function updateTimer() {
        const lastClaimTime = new Date(config.lastClaim).getTime();
        const cooldownMs = (config.cooldownHours || 24) * 60 * 60 * 1000;
        const nextClaimTime = lastClaimTime + cooldownMs;
        const now = Date.now();
        const remaining = nextClaimTime - now;
        
        if (remaining <= 0) {
            if (btn) {
                btn.classList.remove('disabled');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-gift"></i> {{ __("dailyrewards.widget.claim") }}';
            }
            if (cooldownEl) {
                cooldownEl.style.display = 'none';
            }
            window.location.reload();
            return;
        }
        
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
        const pad = n => n.toString().padStart(2, '0');
        timeEl.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
}
</script>