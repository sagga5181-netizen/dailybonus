<?php

namespace DailyRewards\Database\Entities;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\Relation;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'daily_rewards_config')]
class DailyRewardConfig
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $key;

    #[Column(type: 'text', nullable: true)]
    public ?string $value = null;
}