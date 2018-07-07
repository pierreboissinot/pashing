<?php

namespace App\EventHandler;

use App\Service\Wrike;
use Sse\Event;

class ReserveEventHandler implements Event
{
    /**
     * @var string
     */
    private $folderId;
    /**
     * @var Wrike
     */
    private $wrike;

    public function __construct(string $folderId, Wrike $wrike)
    {
        $this->folderId = $folderId;
        $this->wrike = $wrike;
    }

    public function update()
    {
        return $this->wrike->getFolderMetrics($this->folderId);
    }

    public function check()
    {
        return true;
    }
}
