<div class="daily-bonus-settings">
    <ul class="nav nav-tabs" id="bonusSettingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Основное
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rewards-tab" data-bs-toggle="tab" data-bs-target="#rewards" type="button" role="tab">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Награды
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="display-tab" data-bs-toggle="tab" data-bs-target="#display" type="button" role="tab">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                Отображение
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview" type="button" role="tab">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Предпросмотр
            </button>
        </li>
    </ul>

    <div class="tab-content pt-3" id="bonusSettingsTabsContent">
        <!-- Основное -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Сумма бонуса</label>
                    <div class="input-group">
                        <span class="input-group-text">₽</span>
                        <input type="number" name="bonus_amount" class="form-control" value="{{ $settings['bonus_amount'] ?? 100 }}" min="1" max="100000">
                    </div>
                    <small class="text-muted">Базовая сумма для каждого дня</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Количество дней</label>
                    <input type="number" name="days_count" class="form-control" value="{{ $settings['days_count'] ?? 7 }}" min="3" max="30">
                    <small class="text-muted">Сколько дней в цикле бонусов</small>
                </div>
            </div>
            <div class="alert alert-light border">
                <h6 class="alert-heading"><strong>Как это работает:</strong></h6>
                <p class="mb-0 small">Пользователь может получать бонус каждый день. После {{
                    $settings['days_count'] ?? 7
                }} дней цикл начинается заново. Всего можно получить <strong class="text-success">{{ ($settings['bonus_amount'] ?? 100) * ($settings['days_count'] ?? 7) }} ₽</strong> за полный цикл.</p>
            </div>
        </div>

        <!-- Награды -->
        <div class="tab-pane fade" id="rewards" role="tabpanel">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="multiplier_mode">
                    <strong>Режим множителя</strong>
                    <p class="mb-0 text-muted small">Сумма = базовая × номер дня (100, 200, 300...)</p>
                </label>
            </div>
            <hr>
            <label class="form-label fw-bold">Индивидуальные награды по дням</label>
            <div class="alert alert-info">
                <strong>Примечание:</strong> Если включён режим множителя, эти настройки игнорируются.
            </div>
            <textarea name="day_rewards" class="form-control font-monospace" rows="5" placeholder='{"1":100,"2":150,"3":200}'>{{ $settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' }}</textarea>
            <div class="mt-2">
                <small class="text-muted">Быстрые шаблоны:</small>
                <div class="btn-group btn-group-sm mt-1">
                    <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('classic')">Одинаковый</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('growth')">Растущий</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('big')">Большой</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('pyramid')">Пирамида</button>
                </div>
            </div>
        </div>

        <!-- Отображение -->
        <div class="tab-pane fade" id="display" role="tabpanel">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="show_timer" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="show_timer">
                    <strong>Показывать таймер</strong>
                    <p class="mb-0 text-muted small">Обратный отсчёт до следующего бонуса</p>
                </label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" {{ ($settings['show_stats'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="show_stats">
                    <strong>Показывать статистику</strong>
                    <p class="mb-0 text-muted small">Количество дней и всего получено</p>
                </label>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Цвет виджета</label>
                    <input type="color" name="widget_color" class="form-control form-control-color" value="{{ $settings['widget_color'] ?? '#1a1a2e' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Акцентный цвет</label>
                    <input type="color" name="accent_color" class="form-control form-control-color" value="{{ $settings['accent_color'] ?? '#ffd700' }}">
                </div>
            </div>
        </div>

        <!-- Предпросмотр -->
        <div class="tab-pane fade" id="preview" role="tabpanel">
            <div class="text-center mb-3">
                <span class="badge bg-primary">Предпросмотр виджета</span>
            </div>
            <div class="preview-widget p-4 rounded" style="background: linear-gradient(135deg, {{ $settings['widget_color'] ?? '#1a1a2e' }} 0%, #16213e 100%); color: #fff;">
                <h5 class="mb-3 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Ежедневный бонус
                </h5>
                <div class="preview-days d-flex gap-2 justify-content-center flex-wrap mb-3">
                    @for($i = 1; $i <= min(($settings['days_count'] ?? 7), 7); $i++)
                        @php
                            $amount = ($settings['multiplier_mode'] ?? false) 
                                ? ($settings['bonus_amount'] ?? 100) * $i 
                                : (isset(json_decode($settings['day_rewards'] ?? '{}', true)[$i]) ? json_decode($settings['day_rewards'] ?? '{}', true)[$i] : ($settings['bonus_amount'] ?? 100));
                        @endphp
                        <div class="preview-day p-2 rounded text-center {{ $i == 1 ? 'bg-warning text-dark' : 'bg-white bg-opacity-10' }}" style="min-width: 50px;">
                            <div class="small fw-bold">День {{ $i }}</div>
                            <div class="small fw-bold">{{ $amount }} ₽</div>
                        </div>
                    @endfor
                </div>
                <button class="btn btn-success w-100">Получить бонус</button>
            </div>
            <div class="mt-3 text-center">
                <small class="text-muted">Также будет выглядеть виджет на сайте</small>
            </div>
        </div>
    </div>
</div>

<style>
.daily-bonus-settings .nav-tabs .nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #495057;
    border: none;
    border-radius: 8px 8px 0 0;
    padding: 10px 16px;
}
.daily-bonus-settings .nav-tabs .nav-link.active {
    background: #fff;
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
}
.daily-bonus-settings .nav-tabs .nav-link:hover {
    border-color: transparent;
}
.daily-bonus-settings .tab-content {
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 8px 8px;
}
.daily-bonus-settings .preview-widget {
    transition: all 0.3s ease;
}
</style>

<script>
function setRewardPreset(type) {
    const presets = {
        'classic': '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}',
        'growth': '{"1":50,"2":100,"3":150,"4":200,"5":300,"6":400,"7":500}',
        'big': '{"1":100,"2":200,"3":300,"4":500,"5":750,"6":1000,"7":2000}',
        'pyramid': '{"1":500,"2":400,"3":300,"4":250,"5":200,"6":150,"7":100}'
    };
    document.querySelector('textarea[name="day_rewards"]').value = presets[type];
}
</script>