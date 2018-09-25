<?php

namespace App\Controller;

use App\EventHandler\ClosedIssuesEventHandler;
use App\EventHandler\NewIssuesEventHandler;
use App\EventHandler\OpenedIssuesEventHandler;
use App\EventHandler\StaleIssuesEventHandler;
use App\EventHandler\WrikeEventHandler;
use App\Service\Gitlab;
use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Sse\SSE;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cest-pas-sorcier")
 */
class CestPasSorcierController extends AbstractController
{
    /**
     * @var MessageFactory
     */
    protected $messageFactory;
    /**
     * @var HttpAsyncClient
     */
    protected $httpClient;

    /**
     * @var Gitlab
     */
    private $gitlab;

    public function __construct(HttpAsyncClient $client, MessageFactory $messageFactory, Gitlab $gitlab)
    {
        $this->httpClient = $client;
        $this->messageFactory = $messageFactory;
        $this->gitlab = $gitlab;
    }

    /**
     * @Route("/events")
     */
    public function events()
    {
        $sse = new SSE();
        $sse->addEventListener('event_gitlab_opened_issues', new OpenedIssuesEventHandler($this->gitlab));
        $sse->addEventListener('event_gitlab_closed_issues', new ClosedIssuesEventHandler($this->gitlab));
        $sse->addEventListener('event_gitlab_stale_issues', new StaleIssuesEventHandler($this->gitlab));
        $sse->addEventListener('event_gitlab_new_issues', new NewIssuesEventHandler($this->gitlab));
        $sse->addEventListener('event_wrike_timelog', new WrikeEventHandler());

        return $sse->createResponse();
    }
}
