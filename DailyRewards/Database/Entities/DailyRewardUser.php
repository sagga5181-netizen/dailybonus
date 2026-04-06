<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'daily_rewards_users')]
class DailyRewardUser extends ActiveRecord
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer', name: 'user_id')]
    public int $userId;

    #[Column(type: 'integer', name: 'current_day', default: 1)]
    public int $currentDay = 1;

    #[Column(type: 'integer', default: 0)]
    public int $streak = 0;

    #[Column(type: 'datetime', name: 'last_claim', nullable: true)]
    public ?\DateTime $lastClaim = null;

    #[Column(type: 'integer', name: 'total_claimed', default: 0)]
    public int $totalClaimed = 0;

    #[Column(type: 'datetime', name: 'created_at')]
    public \DateTime $createdAt;

    #[Column(type: 'datetime', name: 'updated_at')]
    public \DateTime $updatedAt;
}