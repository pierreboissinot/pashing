<?php

namespace App\EventHandler;

use App\Service\Gitlab;
use Sse\Event;

class ClosedIssuesEventHandler implements Event
{
    /**
     * @var Gitlab
     */
    private $gitlab;

    public function __construct(Gitlab $gitlab)
    {
        $this->gitlab = $gitlab;
    }

    public function update()
    {
        //Here's the place to send data
        $count = $this->gitlab->getClosedIssuesTotal(getenv('GITLAB_PROJECT_ID'));

        return json_encode([
            'current' => $count,
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
