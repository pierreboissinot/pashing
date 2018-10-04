<?php

namespace App\EventHandler;

use App\Service\Wrike;
use Sse\Event;

class WrikeEventHandler implements Event
{
    /**
     * @var Wrike
     */
    private $wrike;

    public function __construct(Wrike $wrike)
    {
        $this->wrike = $wrike;
    }

    public function update()
    {
        $timeSpentTotal = $this->wrike->getTimeSpentTotalLastThirtyDays(getenv('WRIKE_FOLDER_ID'));

        //wrike_time_formatted = "#{sum.round(2).to_s.split(".")[0]}:#{((Integer(sum.round(2).to_s.split(".")[1]) / 100.0) * 60).round}"
        $hours = (int) $timeSpentTotal;
        $minutes = round((($timeSpentTotal - $hours) / 100 * 60), 2);
        $formatedMinutes = mb_substr("{$minutes}", 2, 2);
        $wrikeTimeFormatted = "{$hours}:{$formatedMinutes}";

        return json_encode([
            'current' => $timeSpentTotal,
            'status' => 'ok',
            'updatedAt' => time(),
        ]);
    }

    public function check()
    {
        //Here's the place to check when the data needs update
        //sleep(2);
        return true;
    }
}
