<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Flute\Core\Database\Entities\BaseEntity;

use Cycle\Annotated\Annotation\Column;

#[Entity(table: 'daily_rewards_history')]
class DailyRewardHistory extends BaseEntity
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
    public \DateTime $claimedAt;
}