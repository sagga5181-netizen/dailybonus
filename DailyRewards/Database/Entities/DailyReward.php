<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Flute\Core\Database\Entities\BaseEntity;

use Cycle\Annotated\Annotation\Column;

#[Entity(table: 'daily_rewards')]
class DailyReward extends BaseEntity
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer', name: 'day_number')]
    public int $dayNumber;

    #[Column(type: 'string', name: 'reward_type')]
    public string $rewardType;

    #[Column(type: 'double', name: 'reward_value')]
    public float $rewardValue;

    #[Column(type: 'text', name: 'reward_data', nullable: true)]
    public ?string $rewardData = null;

    #[Column(type: 'string', nullable: true)]
    public ?string $icon = null;

    #[Column(type: 'string', nullable: true)]
    public ?string $name = null;

    #[Column(type: 'boolean', name: 'is_active')]
    public bool $isActive = true;
}