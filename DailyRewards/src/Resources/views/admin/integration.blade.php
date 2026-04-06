<!-- Integration Settings Tab -->
<form method="post" action="{{ route('dailyrewards.admin.save') }}">
    @csrf
    <input type="hidden" name="tab" value="integration">

    <div class="form-section">
        <h3><i class="fas fa-link"></i> {{ __('dailyrewards.integration.api_settings') }}</h3>
        <div class="form-group">
            <label>{{ __('dailyrewards.integration.api_key') }}</label>
            <div class="input-group">
                <input type="text" name="api_key" value="{{ $module->getConfig('api_key') }}" class="form-input" id="api-key">
                <button type="button" class="btn btn-secondary" onclick="generateApiKey()">
                    <i class="fas fa-sync"></i> {{ __('dailyrewards.integration.generate') }}
                </button>
            </div>
            <small>{{ __('dailyrewards.integration.api_key_desc') }}</small>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-bell"></i> {{ __('dailyrewards.integration.webhooks') }}</h3>
        <div class="form-group">
            <label>{{ __('dailyrewards.integration.webhook_url') }}</label>
            <input type="url" name="webhook_url" value="{{ $module->getConfig('webhook_url') }}" class="form-input" placeholder="https://...">
            <small>{{ __('dailyrewards.integration.webhook_url_desc') }}</small>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-plug"></i> {{ __('dailyrewards.integration.available_hooks') }}</h3>
        <div class="hooks-list">
            <div class="hook-item">
                <code>dailyrewards.claimed</code>
                <span>{{ __('dailyrewards.integration.hook_claimed_desc') }}</span>
            </div>
            <div class="hook-item">
                <code>dailyrewards.before_claim</code>
                <span>{{ __('dailyrewards.integration.hook_before_claim_desc') }}</span>
            </div>
            <div class="hook-item">
                <code>dailyrewards.streak_continue</code>
                <span>{{ __('dailyrewards.integration.hook_streak_desc') }}</span>
            </div>
            <div class="hook-item">
                <code>dailyrewards.streak_reset</code>
                <span>{{ __('dailyrewards.integration.hook_reset_desc') }}</span>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('dailyrewards.common.save_settings') }}
        </button>
    </div>
</form>