<div class="daily-bonus-settings">
    <div class="form-group mb-3">
        <label class="form-label">{{ __('dailybonus.settings.bonus_amount') }}</label>
        <input type="number" name="bonus_amount" class="form-control" value="{{ $settings['bonus_amount'] ?? 100 }}" min="1" max="10000">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">{{ __('dailybonus.settings.days_count') }}</label>
        <input type="number" name="days_count" class="form-control" value="{{ $settings['days_count'] ?? 7 }}" min="3" max="30">
    </div>
    <div class="form-group mb-3">
        <div class="form-check">
            <input type="checkbox" name="multiplier_mode" class="form-check-input" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="multiplier_mode">{{ __('dailybonus.settings.multiplier_mode') }}</label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">{{ __('dailybonus.settings.day_rewards') }}</label>
        <textarea name="day_rewards" class="form-control" rows="3" placeholder='{"1":100,"2":200,"3":300}'>{{ $settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' }}</textarea>
        <small class="text-muted">JSON формат: {"день": сумма}</small>
    </div>
    <div class="form-group mb-3">
        <div class="form-check">
            <input type="checkbox" name="show_timer" class="form-check-input" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_timer">{{ __('dailybonus.settings.show_timer') }}</label>
        </div>
    </div>
</div>
