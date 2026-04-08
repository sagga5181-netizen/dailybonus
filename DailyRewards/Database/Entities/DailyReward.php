<?php

namespace Flute\Modules\DailyRewards\Database\Entities;

use Cycle\ActiveRecord\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'daily_rewards')]
class DailyReward extends ActiveRecord
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'int')]
    public int $dayNumber;

    #[Column(type: 'string', nullable: true)]
    public ?string $image = null;

    #[Column(type: 'decimal', precision: 10, scale: 2)]
    public float $balance = 0;

    #[Column(type: 'string', nullable: true)]
    public ?string $name = null;

    #[Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[Column(type: 'bool')]
    public bool $isActive = true;
}
