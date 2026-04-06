<div class="daily-bonus-settings settings-sidebar">
    <div class="settings-layout">
        <div class="settings-nav">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    Основные
                </button>
                <button class="nav-link" id="v-pills-rewards-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rewards" type="button" role="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    Награды
                </button>
                <button class="nav-link" id="v-pills-display-tab" data-bs-toggle="pill" data-bs-target="#v-pills-display" type="button" role="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    Оформление
                </button>
                <button class="nav-link" id="v-pills-preview-tab" data-bs-toggle="pill" data-bs-target="#v-pills-preview" type="button" role="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Предпросмотр
                </button>
            </div>
        </div>
        
        <div class="settings-content">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- Основное -->
                <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                    <h5 class="mb-3">Основные настройки</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Сумма бонуса</label>
                            <div class="input-group">
                                <span class="input-group-text">₽</span>
                                <input type="number" name="bonus_amount" class="form-control" value="{{ $settings['bonus_amount'] ?? 100 }}" min="1">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Количество дней</label>
                            <input type="number" name="days_count" class="form-control" value="{{ $settings['days_count'] ?? 7 }}" min="3" max="30">
                        </div>
                    </div>
                    <div class="alert alert-info">
                        За цикл из <strong>{{ $settings['days_count'] ?? 7 }}</strong> дней пользователь получит <strong class="text-success">{{ ($settings['bonus_amount'] ?? 100) * ($settings['days_count'] ?? 7) }} ₽</strong>
                    </div>
                </div>

                <!-- Награды -->
                <div class="tab-pane fade" id="v-pills-rewards" role="tabpanel">
                    <h5 class="mb-3">Настройка наград</h5>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="multiplier_mode" id="multiplier_mode" {{ ($settings['multiplier_mode'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="multiplier_mode"><strong>Режим множителя</strong></label>
                        <small class="d-block text-muted">Сумма = базовая × день (100, 200, 300...)</small>
                    </div>
                    <hr>
                    <label class="form-label">Награды по дням (JSON)</label>
                    <textarea name="day_rewards" class="form-control font-monospace" rows="4">{{ $settings['day_rewards'] ?? '{"1":100,"2":100,"3":100,"4":100,"5":100,"6":100,"7":100}' }}</textarea>
                    <div class="mt-2">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('classic')">Одинаковый</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('growth')">Растущий</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setRewardPreset('big')">Большой</button>
                        </div>
                    </div>
                </div>

                <!-- Оформление -->
                <div class="tab-pane fade" id="v-pills-display" role="tabpanel">
                    <h5 class="mb-3">Оформление виджета</h5>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_timer" id="show_timer" {{ ($settings['show_timer'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_timer"><strong>Показывать таймер</strong></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" {{ ($settings['show_stats'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_stats"><strong>Показывать статистику</strong></label>
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
                </div>

                <!-- Предпросмотр -->
                <div class="tab-pane fade" id="v-pills-preview" role="tabpanel">
                    <h5 class="mb-3">Предпросмотр</h5>
                    <div class="preview-widget p-3 rounded" style="background: linear-gradient(135deg, {{ $settings['widget_color'] ?? '#1a1a2e' }} 0%, #16213e 100%); color: #fff;">
                        <h6 class="d-flex align-items-center gap-2 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            Ежедневный бонус
                        </h6>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            @for($i = 1; $i <= min(($settings['days_count'] ?? 7), 7); $i++)
                                @php
                                    $amount = ($settings['multiplier_mode'] ?? false) ? ($settings['bonus_amount'] ?? 100) * $i : ($settings['bonus_amount'] ?? 100);
                                @endphp
                                <div class="p-2 rounded text-center {{ $i == 1 ? 'bg-warning text-dark' : 'bg-white bg-opacity-10' }}" style="min-width: 45px;">
                                    <div class="small">День {{ $i }}</div>
                                    <div class="small fw-bold">{{ $amount }} ₽</div>
                                </div>
                            @endfor
                        </div>
                        <button class="btn btn-success w-100 mt-3">Получить бонус</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-sidebar .settings-layout {
    display: flex;
    gap: 0;
    min-height: 400px;
}
.settings-sidebar .settings-nav {
    min-width: 180px;
    border-right: 1px solid #dee2e6;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px 0 0 8px;
}
.settings-sidebar .settings-nav .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 5px;
    color: #495057;
    text-align: left;
}
.settings-sidebar .settings-nav .nav-link:hover {
    background: #e9ecef;
}
.settings-sidebar .settings-nav .nav-link.active {
    background: #0d6efd;
    color: #fff;
}
.settings-sidebar .settings-content {
    flex: 1;
    padding: 20px;
    background: #fff;
    border-radius: 0 8px 8px 0;
    border-left: none;
}
.settings-sidebar .settings-content h5 {
    font-size: 16px;
    font-weight: 600;
    color: #212529;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}
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