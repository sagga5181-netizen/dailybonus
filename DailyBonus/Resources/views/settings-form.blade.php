<div class="daily-bonus-settings-form">
    <div class="setting-tabs">
        <div class="tab-header">
            <button type="button" class="tab-link active" onclick="switchTab(this, 'tab-general')">Основные</button>
            <button type="button" class="tab-link" onclick="switchTab(this, 'tab-rewards')">Награды</button>
            <button type="button" class="tab-link" onclick="switchTab(this, 'tab-cycle')">Цикл</button>
            <button type="button" class="tab-link" onclick="switchTab(this, 'tab-display')">Оформление</button>
        </div>
        <div class="tab-content">
            <div id="tab-general" class="tab-pane active">
                <div class="mb-3">
                    <label class="form-label">Базовый бонус</label>
                    <div class="input-group">
                        <span class="input-group-text">₽</span>
                        <input type="number" name="bonus_amount" class="form-control" value="{{ is_array($settings['bonus_amount'] ?? null) ? 100 : ($settings['bonus_amount'] ?? 100) }}" min="1">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Тип награды</label>
                    <select name="reward_type" class="form-control">
                        <option value="balance" {{ ($settings['reward_type'] ?? 'balance') === 'balance' ? 'selected' : '' }}>Баланс (₽)</option>
                        <option value="coins" {{ ($settings['reward_type'] ?? 'balance') === 'coins' ? 'selected' : '' }}>Монеты</option>
                        <option value="points" {{ ($settings['reward_type'] ?? 'balance') === 'points' ? 'selected' : '' }}>Поинты</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Количество дней в цикле</label>
                    <input type="number" name="days_count" class="form-control" value="{{ is_array($settings['days_count'] ?? null) ? 7 : ($settings['days_count'] ?? 7) }}" min="1" max="30">
                </div>
                <div class="alert alert-info">За цикл: <strong class="text-success" id="total-cycle">0</strong> ₽</div>
            </div>

            <div id="tab-rewards" class="tab-pane">
                <div class="days-list" id="days-list">
                    {{-- Days will be rendered here by JavaScript --}}
                </div>
                <button type="button" class="btn btn-add-day" onclick="addDay()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Добавить день
                </button>
            </div>

            <div id="tab-cycle" class="tab-pane">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="cycle_enabled" id="cycle_enabled" {{ ($settings['cycle_enabled'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="cycle_enabled">Цикличность (сброс после N дней)</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="reset_on_miss" id="reset_on_miss" {{ ($settings['reset_on_miss'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="reset_on_miss">Сбросить прогресс если пропущен день</label>
                </div>
                <div class="alert alert-info">
                    <strong>Как это работает:</strong><br>
                    ☑ Цикличность — после N дней прогресс начнётся сначала<br>
                    ☑ Сброс при пропуске — если игрок не зайдёт 2 дня, прогресс начнётся сначала
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

<?php
$dayRewards = [];
if (isset($settings['day_rewards']) && is_string($settings['day_rewards'])) {
    $dayRewards = json_decode($settings['day_rewards'], true) ?? [];
} elseif (is_array($settings['day_rewards'] ?? [])) {
    $dayRewards = $settings['day_rewards'];
}
$bonusAmount = is_array($settings['bonus_amount'] ?? null) ? 100 : ($settings['bonus_amount'] ?? 100);
$jsonDayRewards = json_encode($dayRewards ?: array_fill(1, 7, $bonusAmount));
?>

<input type="hidden" name="day_rewards" id="day_rewards_json" value='{{ $jsonDayRewards }}'>

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
/* Days list */
.daily-bonus-settings-form .days-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.daily-bonus-settings-form .day-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}
.daily-bonus-settings-form .day-item:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
}
.daily-bonus-settings-form .day-number {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}
.daily-bonus-settings-form .day-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.daily-bonus-settings-form .day-label {
    color: rgba(255,255,255,0.6);
    font-size: 12px;
    font-weight: 500;
}
.daily-bonus-settings-form .day-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.daily-bonus-settings-form .day-input {
    width: 100px;
}
.daily-bonus-settings-form .day-amount-input {
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 8px 12px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    width: 120px;
    text-align: right;
}
.daily-bonus-settings-form .day-amount-input:focus {
    outline: none;
    border-color: rgba(255,255,255,0.3);
    background: rgba(0,0,0,0.5);
}
.daily-bonus-settings-form .day-currency {
    color: rgba(255,255,255,0.5);
    font-weight: 500;
}
.daily-bonus-settings-form .day-delete {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: rgba(239, 68, 68, 0.1);
    color: rgba(239, 68, 68, 0.6);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.daily-bonus-settings-form .day-delete:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
.daily-bonus-settings-form .btn-add-day {
    width: 100%;
    padding: 14px;
    margin-top: 12px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px dashed rgba(59, 130, 246, 0.3);
    border-radius: 12px;
    color: #60a5fa;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}
.daily-bonus-settings-form .btn-add-day:hover {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.5);
}
</style>

<script>
function switchTab(btn, tabId) {
    var form = btn.closest('.daily-bonus-settings-form');
    if (!form) {
        // Fallback - just use direct document getElementById
        var tabs = document.querySelectorAll('.daily-bonus-settings-form .tab-link');
        var panes = document.querySelectorAll('.daily-bonus-settings-form .tab-pane');
    } else {
        var tabs = form.querySelectorAll('.tab-link');
        var panes = form.querySelectorAll('.tab-pane');
    }
    
    tabs.forEach(function(t) { t.classList.remove('active'); });
    panes.forEach(function(p) { p.classList.remove('active'); });
    
    btn.classList.add('active');
    var targetPane = document.getElementById(tabId);
    if (targetPane) {
        targetPane.classList.add('active');
    }
}

// Days management - parse JSON properly with fallback
try {
    var daysData = {{ $jsonDayRewards }};
} catch(e) {
    var daysData = {"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100};
}

function renderDays() {
    var container = document.getElementById('days-list');
    if (!container) return;
    
    container.innerHTML = '';
    
    var total = 0;
    var bonusAmount = parseInt(document.querySelector('input[name="bonus_amount"]').value) || 100;
    
    Object.keys(daysData).forEach(function(dayNum) {
        var amount = parseInt(daysData[dayNum]) || 0;
        total += amount;
        
        var dayItem = document.createElement('div');
        dayItem.className = 'day-item';
        dayItem.innerHTML = 
            '<div class="day-number">' + dayNum + '</div>' +
            '<div class="day-content">' +
                '<div class="day-label">День ' + dayNum + '</div>' +
                '<div class="day-input-row">' +
                    '<input type="number" class="form-control day-amount-input" ' +
                        'value="' + amount + '" ' +
                        'onchange="updateDay(' + dayNum + ', this.value)" ' +
                        'min="0" placeholder="0">' +
                    '<span class="day-currency">₽</span>' +
                '</div>' +
            '</div>' +
            '<button type="button" class="day-delete" onclick="deleteDay(' + dayNum + ')" ' + (Object.keys(daysData).length <= 1 ? 'disabled' : '') + '>' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                    '<line x1="18" y1="6" x2="6" y2="18"></line>' +
                    '<line x1="6" y1="6" x2="18" y2="18"></line>' +
                '</svg>' +
            '</button>';
        container.appendChild(dayItem);
    });
    
    document.getElementById('total-cycle').textContent = total;
    document.getElementById('day_rewards_json').value = JSON.stringify(daysData);
}

function addDay() {
    var newDayNum = Math.max(...Object.keys(daysData).map(Number), 0) + 1;
    var bonusAmount = parseInt(document.querySelector('input[name="bonus_amount"]').value) || 100;
    daysData[newDayNum] = bonusAmount;
    renderDays();
}

function deleteDay(dayNum) {
    if (Object.keys(daysData).length <= 1) {
        return; // Don't allow deleting the last day
    }
    delete daysData[dayNum];
    // Re-index keys
    var newData = {};
    var sortedKeys = Object.keys(daysData).map(Number).sort(function(a, b) { return a - b; });
    sortedKeys.forEach(function(key, index) {
        newData[index + 1] = daysData[key];
    });
    daysData = newData;
    renderDays();
}

function updateDay(dayNum, value) {
    daysData[dayNum] = parseInt(value) || 0;
    renderDays();
}

// Update total when bonus amount changes
document.querySelector('input[name="bonus_amount"]').addEventListener('input', function() {
    renderDays();
});

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    renderDays();
});
</script>