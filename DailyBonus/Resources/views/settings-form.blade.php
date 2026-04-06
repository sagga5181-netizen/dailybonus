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
                        <input type="number" name="bonus_amount" class="form-control" value="{{ is_array($settings['bonus_amount'] ?? null) ? 100 : ($settings['bonus_amount'] ?? 100) }}" min="1">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Количество дней</label>
                    <input type="number" name="days_count" class="form-control" value="{{ is_array($settings['days_count'] ?? null) ? 7 : ($settings['days_count'] ?? 7) }}" min="3" max="30">
                </div>
                <div class="alert alert-info">За цикл: <strong class="text-success">{{ (is_array($settings['bonus_amount'] ?? null) ? 100 : ($settings['bonus_amount'] ?? 100)) * (is_array($settings['days_count'] ?? null) ? 7 : ($settings['days_count'] ?? 7)) }} ₽</strong></div>
            </div>

            <div id="tab-rewards" class="tab-pane">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="multiplier_mode">Режим множителя</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Награды по дням (JSON)</label>
                    <textarea name="day_rewards" class="form-control font-monospace" rows="3">{{ is_array($settings['day_rewards'] ?? null) ? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' : ($settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}') }}</textarea>
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
/* Tab header - оставляем как есть, горизонтально сверху */
.daily-bonus-settings-form .setting-tabs .tab-header {
    display: flex;
    margin-bottom: 16px;
    border-radius: 50px;
    padding: 3px;
    gap: 3px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
}
.daily-bonus-settings-form .setting-tabs .tab-link {
    flex: 1;
    padding: 8px 16px;
    cursor: pointer;
    border: none;
    background: transparent;
    color: rgba(255,255,255,0.5);
    font-weight: 500;
    font-size: 13px;
    border-radius: 50px;
    transition: all 0.2s;
    position: relative;
    line-height: 1;
    text-align: center;
}
.daily-bonus-settings-form .setting-tabs .tab-link:hover { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.7); }
.daily-bonus-settings-form .setting-tabs .tab-link.active { color: #fff; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); }
/* Tab content - используем темный фон как в Hero */
.daily-bonus-settings-form .setting-tabs .tab-content { 
    background: rgba(0,0,0,0.2);
    border-radius: 16px;
    padding: 16px;
    border: 1px solid rgba(255,255,255,0.05);
}
.daily-bonus-settings-form .setting-tabs .tab-pane { 
    display: none; 
    flex-direction: column; 
    gap: 16px; 
}
.daily-bonus-settings-form .setting-tabs .tab-pane.active { 
    display: flex; 
}
/* Стильные элементы формы - темная тема */
.daily-bonus-settings-form .mb-3 {
    background: rgba(255,255,255,0.03);
    padding: 12px 16px;
    border-radius: 12px;
    transition: background 0.2s;
    border: 1px solid rgba(255,255,255,0.03);
}
.daily-bonus-settings-form .mb-3:hover {
    background: rgba(255,255,255,0.06);
}
.daily-bonus-settings-form .form-label {
    color: rgba(255,255,255,0.7);
    font-weight: 500;
    font-size: 13px;
    margin-bottom: 8px;
    display: block;
}
.daily-bonus-settings-form .form-control {
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #fff;
    transition: all 0.25s;
    width: 100%;
}
.daily-bonus-settings-form .form-control::placeholder {
    color: rgba(255,255,255,0.3);
}
.daily-bonus-settings-form .form-control:hover {
    border-color: rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.4);
}
.daily-bonus-settings-form .form-control:focus {
    background: rgba(0,0,0,0.5);
    border-color: rgba(255,255,255,0.3);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
    outline: none;
    color: #fff;
}
/* Remove number input arrows for cleaner look */
.daily-bonus-settings-form input[type="number"]::-webkit-outer-spin-button,
.daily-bonus-settings-form input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.daily-bonus-settings-form input[type="number"] {
    -moz-appearance: textfield;
}
.daily-bonus-settings-form .input-group-text {
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
    border-right: none;
    border-radius: 10px 0 0 10px;
    color: rgba(255,255,255,0.6);
    font-weight: 600;
    padding: 12px 14px;
}
.daily-bonus-settings-form .input-group .form-control {
    border-radius: 0 10px 10px 0;
}
.daily-bonus-settings-form textarea.form-control {
    border-radius: 10px;
    font-size: 13px;
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Fira Code', monospace;
    line-height: 1.5;
    resize: vertical;
    min-height: 80px;
}
.daily-bonus-settings-form .btn {
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    padding: 8px 14px;
    transition: all 0.2s;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.8);
}
.daily-bonus-settings-form .btn:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.daily-bonus-settings-form .alert {
    border-radius: 12px;
    border: none;
    padding: 14px 18px;
}
.daily-bonus-settings-form .alert-info {
    background: rgba(59, 130, 246, 0.15);
    color: #60a5fa;
    border: 1px solid rgba(59, 130, 246, 0.2);
}
.daily-bonus-settings-form .text-success {
    color: #4ade80 !important;
}
.daily-bonus-settings-form .form-check {
    padding: 10px 14px;
    border-radius: 10px;
    transition: background 0.2s;
    display: flex;
    align-items: center;
}
.daily-bonus-settings-form .form-check:hover {
    background: rgba(255,255,255,0.05);
}
.daily-bonus-settings-form .form-check-input {
    width: 44px;
    height: 24px;
    border-radius: 12px;
    cursor: pointer;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
}
.daily-bonus-settings-form .form-check-input:checked {
    background: #3b82f6;
    border-color: #3b82f6;
}
.daily-bonus-settings-form .form-check-label {
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    cursor: pointer;
    margin-left: 8px;
}
.daily-bonus-settings-form .form-control-color {
    width: 50px;
    height: 38px;
    padding: 4px;
    border-radius: 8px;
    cursor: pointer;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
}
.daily-bonus-settings-form .row {
    margin: 0 -8px;
}
.daily-bonus-settings-form .col-md-6 {
    padding: 0 8px;
}
.daily-bonus-settings-form .mt-1 {
    margin-top: 8px;
}
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