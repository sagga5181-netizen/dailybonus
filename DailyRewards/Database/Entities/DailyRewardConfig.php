<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Flute\Core\Database\Entities\BaseEntity;


#[Entity(table: 'daily_rewards_config')]
class DailyRewardConfig extends BaseEntity
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $key;

    #[Column(type: 'text', nullable: true)]
    public ?string $value = null;
}