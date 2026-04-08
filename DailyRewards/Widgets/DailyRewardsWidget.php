<?php

namespace Flute\Modules\DailyRewards\Widgets;

use Flute\Modules\DailyRewards\Database\Entities\DailyRewardUser;
use Flute\Modules\DailyRewards\Database\Entities\DailyReward;
use Flute\Core\Modules\Page\Widgets\Contracts\WidgetInterface;

class DailyRewardsWidget implements WidgetInterface
{
    public function getName(): string
    {
        return 'dailyreward';
    }

    public function getIcon(): string
    {
        return 'gift';
    }

    public function getSettings(): array
    {
        return [
            'title' => 'Ежедневные бонусы',
            'show_streak' => true,
            'cooldown_hours' => 24,
            'loop_rewards' => true,
            'show_inactive' => false,
        ];
    }

    public function renderSettingsForm(array $settings): string
    {
        $rewards = \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()->orderBy('dayNumber', 'ASC')->fetchAll();
        
        ob_start();
        ?>
        <form method="POST">
        @csrf
        <ul class="nav nav-tabs mb-3" id="widgetSettingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Общие настройки</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rewards-tab" data-bs-toggle="tab" data-bs-target="#rewards" type="button" role="tab">Управление наградами</button>
            </li>
        </ul>
        
        <div class="tab-content" id="widgetSettingsTabsContent">
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="form-group">
                    <label>Заголовок виджета</label>
                    <input type="text" name="settings[title]" value="<?php echo htmlspecialchars($settings['title'] ?? 'Ежедневные бонусы') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Период кулдауна (часов)</label>
                    <input type="number" name="settings[cooldown_hours]" value="<?php echo (int) ($settings['cooldown_hours'] ?? 24) ?>" class="form-control" min="1">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="settings[show_streak]" <?php echo (($settings['show_streak'] ?? true) ? 'checked' : '') ?>> Показывать текущий стрик
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="settings[loop_rewards]" <?php echo (($settings['loop_rewards'] ?? true) ? 'checked' : '') ?>> Зациклить награды после последнего дня
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="settings[show_inactive]" <?php echo (($settings['show_inactive'] ?? false) ? 'checked' : '') ?>> Показывать неактивные дни
                    </label>
                </div>
            </div>
            
            <div class="tab-pane fade" id="rewards" role="tabpanel">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" onclick="addNewReward()">➕ Добавить новый день</button>
                </div>
                
                <div id="rewardsList" class="space-y-3">
                    <?php foreach ($rewards as $reward): ?>
                    <div class="card reward-item mb-3">
                        <input type="hidden" name="rewards[][id]" value="<?php echo $reward->id ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>День</label>
                                    <input type="number" name="rewards[][dayNumber]" value="<?php echo $reward->dayNumber ?>" class="form-control" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label>Название награды</label>
                                    <input type="text" name="rewards[][name]" value="<?php echo htmlspecialchars($reward->name ?? '') ?>" class="form-control" placeholder="Название награды">
                                </div>
                                <div class="col-md-2">
                                    <label>Баланс</label>
                                    <input type="number" name="rewards[][balance]" step="0.01" min="0" value="<?php echo $reward->balance ?>" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Активно</label><br>
                                    <input type="checkbox" name="rewards[][isActive]" <?php echo ($reward->isActive ? 'checked' : '') ?>>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-danger mt-4" onclick="this.closest('.reward-item').remove()">✕ Удалить</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>URL изображения</label>
                                    <input type="text" name="rewards[][image]" value="<?php echo htmlspecialchars($reward->image ?? '') ?>" class="form-control" placeholder="https://...">
                                </div>
                                <div class="col-md-6">
                                    <label>Описание</label>
                                    <input type="text" name="rewards[][description]" value="<?php echo htmlspecialchars($reward->description ?? '') ?>" class="form-control" placeholder="Описание награды">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-success">💾 Сохранить награды</button>
        </div>
        
        <script>
        function addNewReward() {
            const dayNumber = document.querySelectorAll(".reward-item").length + 1;
            const html = `
                <div class="card reward-item mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label>День</label>
                                <input type="number" name="rewards[][dayNumber]" value="${dayNumber}" class="form-control" min="1">
                            </div>
                            <div class="col-md-4">
                                <label>Название награды</label>
                                <input type="text" name="rewards[][name]" class="form-control" placeholder="Например: Бонус дня 1">
                            </div>
                            <div class="col-md-2">
                                <label>Баланс</label>
                                <input type="number" name="rewards[][balance]" step="0.01" min="0" value="0" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label>Активно</label><br>
                                <input type="checkbox" name="rewards[][isActive]" checked>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-danger mt-4" onclick="this.closest('.reward-item').remove()">✕ Удалить</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>URL изображения</label>
                                <input type="text" name="rewards[][image]" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-6">
                                <label>Описание</label>
                                <input type="text" name="rewards[][description]" class="form-control" placeholder="Описание награды">
                            </div>
                        </div>
                    </div>
                </div>`;
            document.getElementById("rewardsList").insertAdjacentHTML("beforeend", html);
        }
        
        function saveRewards() {
            const rewards = [];
            document.querySelectorAll(".reward-item").forEach(item => {
                const reward = {};
                const id = item.querySelector('input[name="rewards[][id]"]');
                if (id) reward.id = id.value;
                reward.dayNumber = item.querySelector('input[name="rewards[][dayNumber]"]').value;
                reward.name = item.querySelector('input[name="rewards[][name]"]').value;
                reward.balance = item.querySelector('input[name="rewards[][balance]"]').value;
                reward.image = item.querySelector('input[name="rewards[][image]"]').value;
                reward.description = item.querySelector('input[name="rewards[][description]"]').value;
                reward.isActive = item.querySelector('input[name="rewards[][isActive]"]').checked;
                rewards.push(reward);
            });
            
            fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: "saveRewards",
                    rewards: rewards
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("✅ Награды успешно сохранены!");
                }
            });
        }
        </script>
        </form>
        <?php
        return ob_get_clean();
    }

    public function render(array $settings): ?string
    {
        $user = user();
        if (!$user) {
            return '';
        }

        $title = $settings['title'] ?? 'Ежедневные бонусы';
        $showStreak = $settings['show_streak'] ?? true;
        $cooldownHours = $settings['cooldown_hours'] ?? 24;
        $loopRewards = $settings['loop_rewards'] ?? true;
        $showInactive = $settings['show_inactive'] ?? false;

        // Get user progress
        $progress = DailyRewardUser::query()->where('userId', $user->id)->fetchOne();

        // Create progress if not exists
        if (!$progress) {
            $progress = new DailyRewardUser();
            $progress->userId = $user->id;
            $progress->currentDay = 1;
            $progress->streak = 0;
            $progress->save();
        }

        // Get all rewards
        $query = DailyReward::query();
        
        if (!$showInactive) {
            $query->where('isActive', true);
        }
        
        $rewards = $query->orderBy('dayNumber', 'ASC')->fetchAll();

        // Check if can claim
        $canClaim = false;
        
        if ($progress->lastClaim) {
            $lastClaim = $progress->lastClaim instanceof \DateTimeImmutable 
                ? $progress->lastClaim 
                : new \DateTimeImmutable($progress->lastClaim);
            $now = new \DateTimeImmutable();
            $diffHours = ($now->getTimestamp() - $lastClaim->getTimestamp()) / 3600;
            $canClaim = $diffHours >= $cooldownHours;
        } else {
            $canClaim = true;
        }

        $currentDay = $progress->currentDay;
        $streak = $progress->streak;

        // Get current day reward
        $currentReward = null;
        foreach ($rewards as $r) {
            if ($r->dayNumber == $currentDay) {
                $currentReward = $r;
                break;
            }
        }

        return view('dailyrewards::widget.index', [
            'title' => $title,
            'showStreak' => $showStreak,
            'currentDay' => $currentDay,
            'streak' => $streak,
            'canClaim' => $canClaim,
            'rewards' => $rewards,
            'currentReward' => $currentReward,
        ])->render();
    }

    public function validateSettings(array $input): true|array
    {
        $errors = [];

        if (isset($input['cooldown_hours']) && $input['cooldown_hours'] < 1) {
            $errors['cooldown_hours'] = 'Период кулдауна должен быть не менее 1 часа';
        }

        return empty($errors) ? true : $errors;
    }

    public function saveSettings(array $input): array
    {
        // Handle rewards saving
        if (isset($input['rewards']) && is_array($input['rewards'])) {
            $existingIds = [];

            foreach ($input['rewards'] as $rewardData) {
                if (!empty($rewardData['id'])) {
                    $reward = \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()->where('id', $rewardData['id'])->fetchOne();
                    if ($reward) {
                        $reward->dayNumber = (int) $rewardData['dayNumber'];
                        $reward->name = $rewardData['name'] ?? null;
                        $reward->description = $rewardData['description'] ?? null;
                        $reward->image = $rewardData['image'] ?? null;
                        $reward->balance = (float) ($rewardData['balance'] ?? 0);
                        $reward->isActive = (bool) ($rewardData['isActive'] ?? false);
                        $reward->save();

                        $existingIds[] = $reward->id;
                    }
                } else {
                    if (!empty($rewardData['dayNumber'])) {
                        $reward = new \Flute\Modules\DailyRewards\Database\Entities\DailyReward();
                        $reward->dayNumber = (int) $rewardData['dayNumber'];
                        $reward->name = $rewardData['name'] ?? null;
                        $reward->description = $rewardData['description'] ?? null;
                        $reward->image = $rewardData['image'] ?? null;
                        $reward->balance = (float) ($rewardData['balance'] ?? 0);
                        $reward->isActive = (bool) ($rewardData['isActive'] ?? true);
                        $reward->save();

                        $existingIds[] = $reward->id;
                    }
                }
            }

            // Delete removed rewards
            if (!empty($existingIds)) {
                \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            }
        }

        return [
            'title' => $input['title'] ?? 'Ежедневные бонусы',
            'show_streak' => (bool) ($input['show_streak'] ?? false),
            'cooldown_hours' => (int) ($input['cooldown_hours'] ?? 24),
            'loop_rewards' => (bool) ($input['loop_rewards'] ?? false),
            'show_inactive' => (bool) ($input['show_inactive'] ?? false),
        ];
    }

    public function getDefaultWidth(): int
    {
        return 12;
    }

    public function getMinWidth(): int
    {
        return 6;
    }

    public function hasSettings(): bool
    {
        return true;
    }

    public function getButtons(): array
    {
        return [];
    }

    public function handleAction(string $action, ?string $widgetId = null): array
    {
        if ($action === 'saveRewards') {
            $input = request()->all();
            
            if (isset($input['rewards']) && is_array($input['rewards'])) {
                $existingIds = [];
                
                foreach ($input['rewards'] as $rewardData) {
                    if (!empty($rewardData['id'])) {
                        $reward = \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()->where('id', $rewardData['id'])->fetchOne();
                        if ($reward) {
                            $reward->dayNumber = (int) $rewardData['dayNumber'];
                            $reward->name = $rewardData['name'] ?? null;
                            $reward->description = $rewardData['description'] ?? null;
                            $reward->image = $rewardData['image'] ?? null;
                            $reward->balance = (float) ($rewardData['balance'] ?? 0);
                            $reward->isActive = (bool) ($rewardData['isActive'] ?? false);
                            $reward->save();
                            
                            $existingIds[] = $reward->id;
                        }
                    } else {
                        if (!empty($rewardData['dayNumber'])) {
                            $reward = new \Flute\Modules\DailyRewards\Database\Entities\DailyReward();
                            $reward->dayNumber = (int) $rewardData['dayNumber'];
                            $reward->name = $rewardData['name'] ?? null;
                            $reward->description = $rewardData['description'] ?? null;
                            $reward->image = $rewardData['image'] ?? null;
                            $reward->balance = (float) ($rewardData['balance'] ?? 0);
                            $reward->isActive = (bool) ($rewardData['isActive'] ?? true);
                            $reward->save();
                            
                            $existingIds[] = $reward->id;
                        }
                    }
                }
                
                // Удаляем награды которые были убраны из списка
                if (!empty($existingIds)) {
                    \Flute\Modules\DailyRewards\Database\Entities\DailyReward::query()
                        ->whereNotIn('id', $existingIds)
                        ->delete();
                }
            }
            
            return ['success' => true, 'message' => 'Награды сохранены'];
        }
        
        return [];
    }

    public function getCategory(): string
    {
        return 'rewards';
    }
}