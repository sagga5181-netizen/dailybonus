<?php

return [
    // Module
    'module_title' => 'Daily Rewards',
    'module_description' => 'Daily rewards system with streaks and flexible configuration',

    // Tabs
    'tabs' => [
        'general' => 'General',
        'rewards' => 'Rewards',
        'visual' => 'Visual',
        'integration' => 'Integration',
    ],

    // General Settings
    'general' => [
        'module_status' => 'Module Status',
        'enable_module' => 'Enable Module',
        'cooldown_settings' => 'Cooldown Settings',
        'cooldown_hours' => 'Cooldown (hours)',
        'cooldown_hours_desc' => 'Hours between reward claims',
        'timezone' => 'Timezone',
        'streak_settings' => 'Streak Settings',
        'reset_on_miss' => 'Reset streak on missed day',
        'reset_on_miss_desc' => 'If user misses a day, their streak will reset',
        'cycle_settings' => 'Cycle Settings',
        'max_days' => 'Days in cycle',
        'max_days_desc' => 'Number of days in one rewards cycle',
        'days' => 'days',
    ],

    // Rewards
    'rewards' => [
        'days_configured' => 'days configured',
        'generate_rewards' => 'Generate Rewards',
        'edit_reward' => 'Edit Reward',
        'day' => 'Day',
        'no_reward_set' => 'No reward set',
        'name' => 'Reward Name',
        'type' => 'Reward Type',
        'type_currency' => 'Currency',
        'type_item' => 'Item',
        'type_custom' => 'Custom',
        'amount' => 'Amount',
        'item_id' => 'Item ID',
        'quantity' => 'Quantity',
        'custom_data' => 'Data (JSON)',
        'icon' => 'Icon',
        'active' => 'Active',
    ],

    // Visual
    'visual' => [
        'theme_settings' => 'Theme Settings',
        'theme' => 'Theme',
        'theme_default' => 'Default',
        'theme_ocean' => 'Ocean',
        'theme_sunset' => 'Sunset',
        'theme_forest' => 'Forest',
        'theme_royal' => 'Royal',
        'theme_midnight' => 'Midnight',
        'animation_settings' => 'Animation Settings',
        'enable_animations' => 'Enable Animations',
        'text_settings' => 'Text Settings',
        'title_text' => 'Title',
        'button_text' => 'Button Text',
        'cooldown_text' => 'Cooldown Text',
        'custom_css' => 'Custom CSS',
    ],

    // Integration
    'integration' => [
        'api_settings' => 'API Settings',
        'api_key' => 'API Key',
        'api_key_desc' => 'Use this key for external integrations',
        'generate' => 'Generate',
        'webhooks' => 'Webhooks',
        'webhook_url' => 'Webhook URL',
        'webhook_url_desc' => 'URL to send notifications about claimed rewards',
        'available_hooks' => 'Available Hooks',
        'hook_claimed_desc' => 'Called after reward is claimed',
        'hook_before_claim_desc' => 'Called before reward is claimed',
        'hook_streak_desc' => 'Called when streak continues',
        'hook_reset_desc' => 'Called when streak is reset',
    ],

    // Widget
    'widget' => [
        'title' => 'Daily Rewards',
        'subtitle' => 'Visit daily to receive valuable rewards',
        'streak' => 'Streak',
        'day' => 'Day',
        'days' => 'days',
        'total_claimed' => 'claimed',
        'claim' => 'Claim Reward',
        'claimed' => 'Claimed',
        'next_reward' => 'Next reward',
        'congrats' => 'Congratulations!',
        'reward_received' => 'You received your reward!',
    ],

    // Common
    'common' => [
        'save_settings' => 'Save Settings',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'currency' => 'coins',
    ],

    // Messages
    'settings_saved' => 'Settings saved',
    'cooldown_not_expired' => 'Cannot claim reward. Wait for the next day.',
    'reward_not_found' => 'Reward not found',
    'reward_received' => 'Reward received!',
    'unknown_reward_type' => 'Unknown reward type',
];