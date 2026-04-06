<div class="daily-bonus-settings">
    <h5 class="mb-3">Настройки виджета "Ежедневный бонус"</h5>
    
    <div class="mb-3">
        <label class="form-label">Сумма бонуса</label>
        <div class="input-group">
            <span class="input-group-text">₽</span>
            <input type="number" name="bonus_amount" class="form-control" value="{{ $settings['bonus_amount'] ?? 100 }}" min="1">
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Количество дней</label>
        <input type="number" name="days_count" class="form-control" value="{{ $settings['days_count'] ?? 7 }}" min="3" max="30">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Награды по дням (JSON)</label>
        <textarea name="day_rewards" class="form-control font-monospace" rows="3">{{ $settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' }}</textarea>
        <div class="mt-1">
            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="setRewardPreset('classic')">Одинаковый</button>
            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="setRewardPreset('growth')">Растущий</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setRewardPreset('big')">Большой</button>
        </div>
    </div>
    
    <hr>
    
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="multiplier_mode">Режим множителя (база × день)</label>
    </div>
    
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="show_timer" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="show_timer">Показывать таймер</label>
    </div>
    
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" {{ ($settings['show_stats'] ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="show_stats">Показывать статистику</label>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Цвет фона</label>
            <input type="color" name="widget_color" class="form-control form-control-color" value="{{ $settings['widget_color'] ?? '#1a1a2e' }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Акцентный цвет</label>
            <input type="color" name="accent_color" class="form-control form-control-color" value="{{ $settings['accent_color'] ?? '#ffd700' }}">
        </div>
    </div>
    
    <div class="alert alert-info mt-3">
        За цикл из <strong>{{ $settings['days_count'] ?? 7 }}</strong> дней пользователь получит <strong class="text-success">{{ ($settings['bonus_amount'] ?? 100) * ($settings['days_count'] ?? 7) }} ₽</strong>
    </div>
</div>

<script>
function setRewardPreset(type) {
    const presets = {
        'classic': '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}',
        'growth': '{"1":50,"2":100,"3":150,"4":200,"5":300,"6":400,"7":500}',
        'big': '{"1":100,"2":200,"3":300,"4":500,"5":750,"6":1000,"7":2000}'
    };
    document.querySelector('textarea[name="day_rewards"]').value = presets[type];
}
</script>