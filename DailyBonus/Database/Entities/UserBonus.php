<?php

namespace Flute\Modules\DailyBonus\Database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(table: 'dailybonus_history')]
#[Table(indexes: [
    new Index(columns: ['user_id', 'claimed_at']),
    new Index(columns: ['user_id', 'cycle_count']),
])]
class UserBonus
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'integer')]
    public int $user_id;

    #[Column(type: 'float')]
    public float $amount;

    #[Column(type: 'integer')]
    public int $day_number;

    #[Column(type: 'integer')]
    public int $cycle_count;

    #[Column(type: 'string')]
    public string $reward_type;

    #[Column(type: 'string', nullable: true)]
    public ?string $reward_item;

    #[Column(type: 'datetime')]
    public string $claimed_at;
}
