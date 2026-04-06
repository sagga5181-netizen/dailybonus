<!-- Rewards Tab -->
<form method="post" action="{{ route('dailyrewards.admin.save') }}">
    @csrf
    <input type="hidden" name="tab" value="rewards">

    <div class="rewards-header">
        <div class="rewards-info">
            <span class="badge">{{ count($rewards) }} / {{ $maxDays }} {{ __('dailyrewards.rewards.days_configured') }}</span>
        </div>
        <div class="rewards-actions">
            <button type="button" class="btn btn-success" onclick="generateRewards()">
                <i class="fas fa-magic"></i> {{ __('dailyrewards.rewards.generate_rewards') }}
            </button>
        </div>
    </div>

    <div class="rewards-grid">
        @for($i = 1; $i <= $maxDays; $i++)
            @php
                $reward = null;
                foreach($rewards as $r) {
                    if($r->dayNumber == $i) { $reward = $r; break; }
                }
            @endphp
            <div class="reward-card {{ $reward ? 'active' : 'empty' }}" data-day="{{ $i }}">
                <div class="reward-day">{{ __('dailyrewards.rewards.day') }} {{ $i }}</div>
                
                @if($reward)
                <div class="reward-content">
                    <div class="reward-icon">
                        <i class="fas fa-{{ $reward->icon ?? 'gift' }}"></i>
                    </div>
                    <div class="reward-info">
                        <span class="reward-name">{{ $reward->name }}</span>
                        <span class="reward-value">{{ number_format($reward->rewardValue) }} {{ __('dailyrewards.common.currency') }}</span>
                        <span class="reward-type">{{ __('dailyrewards.rewards.type_' . $reward->rewardType) }}</span>
                    </div>
                </div>
                <div class="reward-actions">
                    <button type="button" class="btn-icon" onclick="editReward({{ $i }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn-icon btn-danger" onclick="deleteReward({{ $i }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @else
                <div class="reward-empty">
                    <i class="fas fa-plus-circle"></i>
                    <span>{{ __('dailyrewards.rewards.no_reward_set') }}</span>
                </div>
                @endif
            </div>
        @endfor
    </div>
</form>

<!-- Edit Reward Modal -->
<div id="reward-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('dailyrewards.rewards.edit_reward') }} <span id="modal-day"></span></h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="reward-form">
                <input type="hidden" name="day_number" id="day-number" value="">
                
                <div class="form-group">
                    <label>{{ __('dailyrewards.rewards.name') }}</label>
                    <input type="text" name="name" id="reward-name" class="form-input">
                </div>
                
                <div class="form-group">
                    <label>{{ __('dailyrewards.rewards.type') }}</label>
                    <select name="reward_type" id="reward-type" class="form-select" onchange="toggleRewardFields()">
                        <option value="currency">{{ __('dailyrewards.rewards.type_currency') }}</option>
                        <option value="item">{{ __('dailyrewards.rewards.type_item') }}</option>
                        <option value="custom">{{ __('dailyrewards.rewards.type_custom') }}</option>
                    </select>
                </div>
                
                <div class="form-group" id="currency-field">
                    <label>{{ __('dailyrewards.rewards.amount') }}</label>
                    <input type="number" name="reward_value" id="reward-value" class="form-input" min="0" step="0.01" value="0">
                </div>
                
                <div class="form-group" id="item-field" style="display:none;">
                    <label>{{ __('dailyrewards.rewards.item_id') }}</label>
                    <input type="number" name="item_id" id="item-id" class="form-input" min="1" value="1">
                    <label style="margin-top:10px;">{{ __('dailyrewards.rewards.quantity') }}</label>
                    <input type="number" name="quantity" id="item-quantity" class="form-input" min="1" value="1">
                </div>
                
                <div class="form-group" id="custom-field" style="display:none;">
                    <label>{{ __('dailyrewards.rewards.custom_data') }}</label>
                    <textarea name="custom_data" id="custom-data" class="form-textarea" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>{{ __('dailyrewards.rewards.icon') }}</label>
                    <select name="icon" id="reward-icon" class="form-select">
                        @foreach(['coins', 'gift', 'star', 'trophy', 'diamond', 'heart', 'bolt', 'gem'] as $icon)
                        <option value="{{ $icon }}">{{ $icon }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="toggle">
                        <input type="checkbox" name="is_active" id="reward-active" value="1" checked>
                        <span class="toggle-slider"></span>
                        <span class="toggle-label">{{ __('dailyrewards.rewards.active') }}</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">{{ __('dailyrewards.common.cancel') }}</button>
            <button type="button" class="btn btn-primary" onclick="saveReward()">{{ __('dailyrewards.common.save') }}</button>
        </div>
    </div>
</div>