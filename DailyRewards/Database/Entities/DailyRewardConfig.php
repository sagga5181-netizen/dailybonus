<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'daily_rewards_config')]
class DailyRewardConfig extends ActiveRecord
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $key;

    #[Column(type: 'text', nullable: true)]
    public ?string $value = null;
}