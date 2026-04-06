<div class="daily-bonus-settings-form">
    <div class="setting-tabs">
        <div class="tab-header">
            <button type="button" class="tab-link active" onclick="switchTab(this, 'tab-general')">Основные</button>
            <button type="button" class="tab-link" onclick="switchTab(this, 'tab-rewards')">Награды</button>
            <button type="button" class="tab-link" onclick="switchTab(this, 'tab-display')">Оформление</button>
        </div>
        <div class="tab-content">
            <div id="tab-general" class="tab-pane active">
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
                <div class="alert alert-info">За цикл: <strong class="text-success">{{ ($settings['bonus_amount'] ?? 100) * ($settings['days_count'] ?? 7) }} ₽</strong></div>
            </div>

            <div id="tab-rewards" class="tab-pane">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="multiplier_mode">Режим множителя</label>
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
            </div>

            <div id="tab-display" class="tab-pane">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_timer" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_timer">Показывать таймер</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" {{ ($settings['show_stats'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_stats">Показывать статистику</label>
                </div>
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
            </div>
        </div>
    </div>
</div>

<style>
.daily-bonus-settings-form { min-width: 500px; }
.daily-bonus-settings-form .setting-tabs .tab-header {
    display: flex;
    margin-bottom: 16px;
    border-radius: 50px;
    padding: 3px;
    gap: 3px;
    border: 1px solid rgba(0,0,0,0.1);
    background: rgba(0,0,0,0.03);
}
.daily-bonus-settings-form .setting-tabs .tab-link {
    flex: 1;
    padding: 8px 16px;
    cursor: pointer;
    border: none;
    background: transparent;
    color: #6c757d;
    font-weight: 500;
    font-size: 13px;
    border-radius: 50px;
    transition: all 0.2s;
    line-height: 1;
    text-align: center;
}
.daily-bonus-settings-form .setting-tabs .tab-link:hover { background: rgba(0,0,0,0.05); color: #495057; }
.daily-bonus-settings-form .setting-tabs .tab-link.active { color: #fff; background: #0d6efd; }
.daily-bonus-settings-form .setting-tabs .tab-content { background: transparent; }
.daily-bonus-settings-form .setting-tabs .tab-pane { display: none; }
.daily-bonus-settings-form .setting-tabs .tab-pane.active { display: block; }
</style>

<script>
function switchTab(btn, tabId) {
    var form = btn.closest('.daily-bonus-settings-form');
    var tabs = form.querySelectorAll('.tab-link');
    var panes = form.querySelectorAll('.tab-pane');

    tabs.forEach(function(t) { t.classList.remove('active'); });
    panes.forEach(function(p) { p.classList.remove('active'); });

    btn.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function setRewardPreset(type) {
    var presets = {
        'classic': '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}',
        'growth': '{"1":50,"2":100,"3":150,"4":200,"5":300,"6":400,"7":500}',
        'big': '{"1":100,"2":200,"3":300,"4":500,"5":750,"6":1000,"7":2000}'
    };
    document.querySelector('textarea[name="day_rewards"]').value = presets[type];
}
</script>
