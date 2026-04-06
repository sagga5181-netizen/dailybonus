<?php

namespace Flute\Modules\DailyRewards\Admin\Screens;

use Flute\Core\Admin\UI\Screen;
use Flute\Core\Admin\UI\Layout\LayoutFactory;
use Flute\Core\Admin\UI\Components\Table;
use Flute\Core\Admin\UI\Components\Tabs\Tab;
use Flute\Modules\DailyRewards\Database\Entities\DailyReward;

class DailyRewardsScreen extends Screen
{
    public function render(): string
    {
        $rewards = DailyReward::query()->orderBy('dayNumber', 'ASC')->fetchAll();

        return $this->admin()
            ->title('Ежедневные бонусы')
            ->content(
                LayoutFactory::tabs([
                    Tab::make('Награды')->layouts([
                        $this->createRewardsTable($rewards),
                    ]),
                ])->render()
            );
    }

    private function createRewardsTable(array $rewards): Table
    {
        return Table::make('daily_rewards')
            ->columns([
                'day' => 'День',
                'image' => 'Изображение',
                'balance' => 'Баланс',
                'actions' => '',
            ])
            ->data($rewards)
            ->render();
    }

    public function saveReward(): void
    {
        $data = request()->all();
        
        if (!empty($data['id'])) {
            $reward = DailyReward::query()->where('id', $data['id'])->fetchOne();
            if ($reward) {
                $reward->dayNumber = $data['dayNumber'];
                $reward->image = $data['image'];
                $reward->balance = $data['balance'];
                $reward->save();
            }
        } else {
            $reward = new DailyReward();
            $reward->dayNumber = $data['dayNumber'];
            $reward->image = $data['image'];
            $reward->balance = $data['balance'];
            $reward->isActive = true;
            $reward->save();
        }

        redirect()->back();
    }

    public function deleteReward(int $id): void
    {
        $reward = DailyReward::query()->where('id', $id)->fetchOne();
        if ($reward) {
            $reward->delete();
        }

        redirect()->back();
    }
}
