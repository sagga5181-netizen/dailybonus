<?php

return [
    // Module
    'module_title' => 'Ежедневные награды',
    'module_description' => 'Система ежедневных наград с сериями и гибкой настройкой',

    // Tabs
    'tabs' => [
        'general' => 'Общие',
        'rewards' => 'Награды',
        'visual' => 'Визуал',
        'integration' => 'Интеграции',
    ],

    // General Settings
    'general' => [
        'module_status' => 'Статус модуля',
        'enable_module' => 'Включить модуль',
        'cooldown_settings' => 'Настройки обновления',
        'cooldown_hours' => 'Время обновления (часов)',
        'cooldown_hours_desc' => 'Сколько часов должно пройти между получением наград',
        'timezone' => 'Часовой пояс',
        'streak_settings' => 'Настройки серии',
        'reset_on_miss' => 'Сбросить серию при пропуске дня',
        'reset_on_miss_desc' => 'Если пользователь пропустит день, его серия начнется заново',
        'cycle_settings' => 'Настройки цикла',
        'max_days' => 'Количество дней в цикле',
        'max_days_desc' => 'Количество дней для одного цикла наград',
        'days' => 'дней',
    ],

    // Rewards
    'rewards' => [
        'days_configured' => 'дней настроено',
        'generate_rewards' => 'Сгенерировать награды',
        'edit_reward' => 'Редактировать награду',
        'day' => 'День',
        'no_reward_set' => 'Награда не установлена',
        'name' => 'Название награды',
        'type' => 'Тип награды',
        'type_currency' => 'Валюта',
        'type_item' => 'Предмет',
        'type_custom' => 'Кастомная',
        'amount' => 'Количество',
        'item_id' => 'ID предмета',
        'quantity' => 'Количество',
        'custom_data' => 'Данные (JSON)',
        'icon' => 'Иконка',
        'active' => 'Активно',
    ],

    // Visual
    'visual' => [
        'theme_settings' => 'Настройки темы',
        'theme' => 'Тема',
        'theme_default' => 'Default',
        'theme_ocean' => 'Ocean',
        'theme_sunset' => 'Sunset',
        'theme_forest' => 'Forest',
        'theme_royal' => 'Royal',
        'theme_midnight' => 'Midnight',
        'animation_settings' => 'Настройки анимации',
        'enable_animations' => 'Включить анимации',
        'text_settings' => 'Настройки текста',
        'title_text' => 'Заголовок',
        'button_text' => 'Текст кнопки',
        'cooldown_text' => 'Текст ожидания',
        'custom_css' => 'Пользовательский CSS',
    ],

    // Integration
    'integration' => [
        'api_settings' => 'Настройки API',
        'api_key' => 'API ключ',
        'api_key_desc' => 'Используйте этот ключ для внешних интеграций',
        'generate' => 'Сгенерировать',
        'webhooks' => 'Вебхуки',
        'webhook_url' => 'URL вебхука',
        'webhook_url_desc' => 'URL для отправки уведомлений о полученных наградах',
        'available_hooks' => 'Доступные хуки',
        'hook_claimed_desc' => 'Вызывается после получения награды',
        'hook_before_claim_desc' => 'Вызывается перед получением награды',
        'hook_streak_desc' => 'Вызывается при продолжении серии',
        'hook_reset_desc' => 'Вызывается при сбросе серии',
    ],

    // Widget
    'widget' => [
        'title' => 'Ежедневные награды',
        'subtitle' => 'Заходите каждый день и получайте ценные награды',
        'streak' => 'Серия',
        'day' => 'День',
        'days' => 'дней',
        'total_claimed' => 'получено',
        'claim' => 'Получить награду',
        'claimed' => 'Получено',
        'next_reward' => 'Следующая награда',
        'congrats' => 'Поздравляем!',
        'reward_received' => 'Вы получили награду!',
    ],

    // Common
    'common' => [
        'save_settings' => 'Сохранить настройки',
        'save' => 'Сохранить',
        'cancel' => 'Отмена',
        'currency' => 'монет',
    ],

    // Messages
    'settings_saved' => 'Настройки сохранены',
    'cooldown_not_expired' => 'Нельзя получить награду. Подождите следующего дня.',
    'reward_not_found' => 'Награда не найдена',
    'reward_received' => 'Награда получена!',
    'unknown_reward_type' => 'Неизвестный тип награды',
];