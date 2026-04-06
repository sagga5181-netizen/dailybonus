<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

use Cycle\ORM\Entity\Behavior;

#[Entity(table: 'daily_rewards_history')]
#[Behavior\CreatedAt(field: 'createdAt', column: 'created_at')]
class DailyRewardHistory extends ActiveRecord
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer', name: 'user_id')]
    public int $userId;

    #[Column(type: 'integer', name: 'day_number')]
    public int $dayNumber;

    #[Column(type: 'string', name: 'reward_type')]
    public string $rewardType;

    #[Column(type: 'double', name: 'reward_value')]
    public float $rewardValue;

    #[Column(type: 'datetime', name: 'claimed_at')]
    public \DateTimeImmutable $claimedAt;
}