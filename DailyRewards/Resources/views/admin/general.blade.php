<!-- General Settings Tab -->
<form method="post" action="{{ route('dailyrewards.admin.save') }}">
    @csrf
    <input type="hidden" name="tab" value="general">

    <div class="form-section">
        <h3><i class="fas fa-power-off"></i> {{ __('dailyrewards.general.module_status') }}</h3>
        
        <div class="form-group">
            <label class="toggle">
                <input type="checkbox" name="enabled" value="1" {{ $module->getConfig('enabled') ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
                <span class="toggle-label">{{ __('dailyrewards.general.enable_module') }}</span>
            </label>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-clock"></i> {{ __('dailyrewards.general.cooldown_settings') }}</h3>
        
        <div class="form-group">
            <label>{{ __('dailyrewards.general.cooldown_hours') }}</label>
            <input type="number" name="cooldown_hours" value="{{ $module->getConfig('cooldown_hours', 24) }}" min="1" max="168" class="form-input">
            <small>{{ __('dailyrewards.general.cooldown_hours_desc') }}</small>
        </div>

        <div class="form-group">
            <label>{{ __('dailyrewards.general.timezone') }}</label>
            <select name="timezone" class="form-select">
                @foreach(['UTC', 'Europe/Moscow', 'Europe/Kiev', 'Europe/Minsk', 'Asia/Tashkent', 'America/New_York', 'America/Los_Angeles'] as $tz)
                <option value="{{ $tz }}" {{ $module->getConfig('timezone') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-fire"></i> {{ __('dailyrewards.general.streak_settings') }}</h3>
        
        <div class="form-group">
            <label class="toggle">
                <input type="checkbox" name="reset_on_miss" value="1" {{ $module->getConfig('reset_on_miss') ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
                <span class="toggle-label">{{ __('dailyrewards.general.reset_on_miss') }}</span>
            </label>
            <small>{{ __('dailyrewards.general.reset_on_miss_desc') }}</small>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-calendar-alt"></i> {{ __('dailyrewards.general.cycle_settings') }}</h3>
        
        <div class="form-group">
            <label>{{ __('dailyrewards.general.max_days') }}</label>
            <select name="max_days" class="form-select">
                @foreach([7, 14, 21, 28, 30] as $day)
                <option value="{{ $day }}" {{ $module->getConfig('max_days') == $day ? 'selected' : '' }}>{{ $day }} {{ __('dailyrewards.general.days') }}</option>
                @endforeach
            </select>
            <small>{{ __('dailyrewards.general.max_days_desc') }}</small>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('dailyrewards.common.save_settings') }}
        </button>
    </div>
</form>