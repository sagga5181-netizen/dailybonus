# Daily Rewards Module for Flute CMS

Система ежедневных наград с сериями и гибкой настройкой для Flute CMS.

## Установка

1. Скопируйте папку `DailyRewards` в `app/Modules/`
2. Активируйте модуль через админ-панель
3. Модуль автоматически создаст таблицы в базе данных

## Структура

```
DailyRewards/
├── module.json                              # Метаданные модуля
└── src/
    ├── Providers/
    │   └── DailyRewardsServiceProvider.php  # Service Provider
    ├── Database/Entities/                    # Сущности (Cycle ORM)
    │   ├── DailyRewardConfig.php
    │   ├── DailyReward.php
    │   ├── DailyRewardUser.php
    │   └── DailyRewardHistory.php
    ├── Http/Controllers/
    │   └── DailyRewardsController.php        # API контроллер
    ├── Admin/Screens/
    │   └── DailyRewardsScreen.php            # Admin Screen
    ├── Services/
    │   └── DailyRewardsService.php           # Основной сервис
    ├── Widgets/
    │   └── DailyRewardsWidget.php            # Widget
    ├── Routes/
    │   └── web.php                           # Роуты
    ├── Resources/
    │   ├── views/
    │   │   ├── admin/                        # Шаблоны админки
    │   │   │   ├── index.blade.php
    │   │   │   ├── general.blade.php
    │   │   │   ├── rewards.blade.php
    │   │   │   ├── visual.blade.php
    │   │   │   └── integration.blade.php
    │   │   └── widget/
    │   │       └── index.blade.php          # Widget шаблон
    │   ├── assets/scss/
    │   │   └── daily-rewards.scss           # Стили (SCSS)
    │   └── lang/
    │       ├── ru.php
    │       └── en.php
```

## Использование

### Виджет на сайте

```php
// В шаблоне Blade
@widget('dailyrewards')
```

### API Endpoints

- `POST /api/daily-rewards/claim` - Получить награду
- `GET /api/daily-rewards/status` - Статус пользователя
- `GET /api/daily-rewards/history` - История наград
- `GET /api/daily-rewards/can-claim` - Проверить доступность

### События (Events)

- `dailyrewards.claimed` - После получения награды
- `dailyrewards.before_claim` - Перед получением
- `dailyrewards.streak_continue` - При продолжении серии
- `dailyrewards.streak_reset` - При сбросе серии
- `dailyrewards.custom` - Для кастомных наград

## Конфигурация

Настройки модуля (через админ-панель):

- `enabled` - Включить/выключить модуль
- `cooldown_hours` - Время между получениями (по умолчанию 24ч)
- `timezone` - Часовой пояс
- `reset_on_miss` - Сброс серии при пропуске дня
- `max_days` - Количество дней в цикле
- `theme` - Тема оформления (default, ocean, sunset, forest, royal, midnight)
- `show_animations` - Включить анимации

## Лицензия

MIT