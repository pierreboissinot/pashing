<?php

namespace App\Controller;


use App\EventHandler\BarEventHandler;
use App\EventHandler\FooEventHandler;
use App\EventHandler\OpenedIssuesEventHandler;
use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Psr\Log\LoggerInterface;
use Sse\SSE;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sample")
 */
class SampleController extends AbstractController
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
        //$sse->addEventListener('event_foo', new FooEventHandler());
        //$sse->addEventListener('event_bar', new BarEventHandler());
        $sse->addEventListener('event_gitlab', new OpenedIssuesEventHandler());
        return $sse->createResponse();
    }
}