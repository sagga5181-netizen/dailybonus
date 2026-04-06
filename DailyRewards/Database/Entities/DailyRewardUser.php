<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

use Cycle\ORM\Entity\Behavior;

#[Entity(table: 'daily_rewards_users')]
#[Behavior\CreatedAt(field: 'createdAt', column: 'created_at')]
#[Behavior\UpdatedAt(field: 'updatedAt', column: 'updated_at')]
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
    public ?\DateTimeImmutable $lastClaim = null;

    #[Column(type: 'integer', name: 'total_claimed', default: 0)]
    public int $totalClaimed = 0;

    #[Column(type: 'datetime')]
    public \DateTimeImmutable $createdAt;

    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTimeImmutable $updatedAt = null;
}