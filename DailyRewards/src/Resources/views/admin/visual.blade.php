<!-- Visual Settings Tab -->
<form method="post" action="{{ route('dailyrewards.admin.save') }}">
    @csrf
    <input type="hidden" name="tab" value="visual">

    <div class="form-section">
        <h3><i class="fas fa-palette"></i> {{ __('dailyrewards.visual.theme_settings') }}</h3>
        <div class="form-group">
            <label>{{ __('dailyrewards.visual.theme') }}</label>
            <div class="theme-grid">
                @foreach(['default' => '#6366f1', 'ocean' => '#0ea5e9', 'sunset' => '#f97316', 'forest' => '#22c55e', 'royal' => '#8b5cf6', 'midnight' => '#1e293b'] as $themeId => $color)
                <div class="theme-option {{ $module->getConfig('theme') === $themeId ? 'active' : '' }}" data-theme="{{ $themeId }}" onclick="selectTheme('{{ $themeId }}')">
                    <div class="theme-preview" style="background: {{ $color }}"></div>
                    <span>{{ __('dailyrewards.visual.theme_' . $themeId) }}</span>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="theme" id="selected-theme" value="{{ $module->getConfig('theme', 'default') }}">
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-animation"></i> {{ __('dailyrewards.visual.animation_settings') }}</h3>
        <div class="form-group">
            <label class="toggle">
                <input type="checkbox" name="show_animations" value="1" {{ $module->getConfig('show_animations') ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
                <span class="toggle-label">{{ __('dailyrewards.visual.enable_animations') }}</span>
            </label>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-font"></i> {{ __('dailyrewards.visual.text_settings') }}</h3>
        <div class="form-group">
            <label>{{ __('dailyrewards.visual.title_text') }}</label>
            <input type="text" name="title_text" value="{{ $module->getConfig('title_text') }}" class="form-input">
        </div>
        <div class="form-group">
            <label>{{ __('dailyrewards.visual.button_text') }}</label>
            <input type="text" name="button_text" value="{{ $module->getConfig('button_text') }}" class="form-input">
        </div>
        <div class="form-group">
            <label>{{ __('dailyrewards.visual.cooldown_text') }}</label>
            <input type="text" name="cooldown_text" value="{{ $module->getConfig('cooldown_text') }}" class="form-input">
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-code"></i> {{ __('dailyrewards.visual.custom_css') }}</h3>
        <div class="form-group">
            <textarea name="custom_css" class="form-textarea code" rows="8">{{ $module->getConfig('custom_css') }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('dailyrewards.common.save_settings') }}
        </button>
    </div>
</form>