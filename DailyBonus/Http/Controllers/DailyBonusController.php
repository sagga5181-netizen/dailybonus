<?php

namespace Flute\Modules\DailyBonus\Http\Controllers;

use Flute\Core\Support\BaseController;
use Flute\Core\Router\Annotations\Route;
use Flute\Modules\DailyBonus\Widgets\DailyBonusWidget;

class DailyBonusController extends BaseController
{
    #[Route('/dailybonus/claim', name: 'dailybonus.claim', methods: ['POST'])]
    public function claim()
    {
        $widget = new DailyBonusWidget();
        $result = $widget->handleAction('claim', request()->input('widget_id'));
        
        return response()->json($result);
    }
}
