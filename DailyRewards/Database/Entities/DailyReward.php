<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

use Cycle\ORM\Entity\Behavior;

#[Entity(table: 'daily_rewards')]
#[Behavior\CreatedAt(field: 'createdAt', column: 'created_at')]
#[Behavior\UpdatedAt(field: 'updatedAt', column: 'updated_at')]
class DailyReward extends ActiveRecord
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