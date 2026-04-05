{{-- Resources/views/widgets/daily-bonus-settings.blade.php --}}
<div class="widget-settings">
    {{-- Сумма бонуса --}}
    <div class="mb-3">
        <label for="bonus_amount" class="form-label">
            {{ __('dailybonus.settings.bonus_amount') }}
        </label>
        <input type="number" 
               id="bonus_amount" 
               name="bonus_amount" 
               class="form-control"
               value="{{ $settings['bonus_amount'] ?? 100 }}" 
               min="1" 
               max="10000"
               required>
        <small class="form-text text-muted">
            {{ __('dailybonus.settings.bonus_amount_desc') }}
        </small>
    </div>

    {{-- Количество дней --}}
    <div class="mb-3">
        <label for="days_count" class="form-label">
            {{ __('dailybonus.settings.days_count') }}
        </label>
        <input type="number" 
               id="days_count" 
               name="days_count" 
               class="form-control"
               value="{{ $settings['days_count'] ?? 7 }}" 
               min="3" 
               max="30"
               required>
        <small class="form-text text-muted">
            {{ __('dailybonus.settings.days_count_desc') }}
        </small>
    </div>

    {{-- Режим множителя --}}
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" 
                   id="multiplier_mode" 
                   name="multiplier_mode"
                   value="1" 
                   class="form-check-input"
                   {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
            <label for="multiplier_mode" class="form-check-label">
                {{ __('dailybonus.settings.multiplier_mode') }}
            </label>
        </div>
        <small class="form-text text-muted">
            {{ __('dailybonus.settings.multiplier_mode_desc') }}
        </small>
    </div>

    {{-- Показывать таймер --}}
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" 
                   id="show_timer" 
                   name="show_timer"
                   value="1" 
                   class="form-check-input"
                   {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
            <label for="show_timer" class="form-check-label">
                {{ __('dailybonus.settings.show_timer') }}
            </label>
        </div>
        <small class="form-text text-muted">
            {{ __('dailybonus.settings.show_timer_desc') }}
        </small>
    </div>
</div>