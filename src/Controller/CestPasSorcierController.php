<?php

namespace App\Controller;


use App\EventHandler\AllIssuesEventHandler;
use App\EventHandler\BarEventHandler;
use App\EventHandler\ClosedIssuesEventHandler;
use App\EventHandler\FooEventHandler;
use App\EventHandler\NewIssuesEventHandler;
use App\EventHandler\OpenedIssuesEventHandler;
use App\EventHandler\StaleIssuesEventHandler;
use App\EventHandler\WrikeEventHandler;
use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Psr\Log\LoggerInterface;
use Sse\SSE;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(HttpAsyncClient $client, MessageFactory $messageFactory, LoggerInterface $logger)
    {
        $this->httpClient = $client;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
    }
    /**
     * @Route("/events")
     */
    public function events()
    {
        $sse = new SSE();
        $sse->addEventListener('event_gitlab_opened_issues', new OpenedIssuesEventHandler());
        $sse->addEventListener('event_gitlab_closed_issues', new ClosedIssuesEventHandler());
        $sse->addEventListener('event_gitlab_stale_issues', new StaleIssuesEventHandler());
        $sse->addEventListener('event_gitlab_new_issues', new NewIssuesEventHandler());
        $sse->addEventListener('event_wrike_timelog', new WrikeEventHandler());
        return $sse->createResponse();
    }
}