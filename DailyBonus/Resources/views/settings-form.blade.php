<div class="daily-bonus-settings p-3">
    <div class="settings-section mb-4">
        <h6 class="settings-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Основные настройки
        </h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Сумма бонуса</label>
                <div class="input-group">
                    <span class="input-group-text">₽</span>
                    <input type="number" name="bonus_amount" class="form-control" value="{{ $settings['bonus_amount'] ?? 100 }}" min="1" max="100000">
                </div>
                <small class="text-muted">Базовая сумма для каждого дня</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Количество дней</label>
                <input type="number" name="days_count" class="form-control" value="{{ $settings['days_count'] ?? 7 }}" min="3" max="30">
                <small class="text-muted">Сколько дней в цикле бонусов</small>
            </div>
        </div>
    </div>

    <div class="settings-section mb-4">
        <h6 class="settings-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Режим начисления
        </h6>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="multiplier_mode">
                <strong>Режим множителя</strong>
                <br><small class="text-muted">Сумма = базовая × номер дня (100, 200, 300...)</small>
            </label>
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="show_timer" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_timer">
                <strong>Показывать таймер</strong>
                <br><small class="text-muted">Обратный отсчёт до следующего бонуса</small>
            </label>
        </div>
    </div>

    <div class="settings-section mb-4">
        <h6 class="settings-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
            Индивидуальные награды по дням
        </h6>
        <div class="alert alert-info small mb-2">
            <strong>Примечание:</strong> Если включён режим множителя, эти настройки игнорируются.
        </div>
        <label class="form-label small">Награды в формате JSON</label>
        <textarea name="day_rewards" class="form-control font-monospace small" rows="4" placeholder='{"1":100,"2":150,"3":200}'>{{ $settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' }}</textarea>
        <div class="mt-2">
            <small class="text-muted">Быстрые шаблоны:</small>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="setRewardPreset('classic')">Классика</button>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="setRewardPreset('growth')">Рост</button>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="setRewardPreset('big')">Большой</button>
        </div>
    </div>

    <div class="settings-section">
        <h6 class="settings-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            Предпросмотр
        </h6>
        <div class="preview-box p-3 rounded" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold">День 1</span>
                <span class="badge bg-warning text-dark">{{ $settings['bonus_amount'] ?? 100 }} ₽</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-bold">День 2</span>
                <span class="badge bg-secondary">{{ ($settings['multiplier_mode'] ?? false) ? (($settings['bonus_amount'] ?? 100) * 2) : ($settings['bonus_amount'] ?? 100) }} ₽</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="small fw-bold">День 3</span>
                <span class="badge bg-secondary">{{ ($settings['multiplier_mode'] ?? false) ? (($settings['bonus_amount'] ?? 100) * 3) : ($settings['bonus_amount'] ?? 100) }} ₽</span>
            </div>
        </div>
    </div>
</div>

<style>
.daily-bonus-settings .settings-title {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
    font-weight: 600;
    padding-bottom: 8px;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 15px;
}
.daily-bonus-settings .settings-title svg { color: #6c757d; }
.daily-bonus-settings .form-check-label strong { color: #212529; }
.daily-bonus-settings .preview-box { font-family: monospace; }
</style>

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