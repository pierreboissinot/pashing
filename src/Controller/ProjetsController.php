<?php

namespace App\Controller;


use App\EventHandler\ReserveEventHandler;
use Sse\SSE;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projets")
 */
class ProjetsController extends AbstractController
{
    /**
     * @Route("/events")
     */
    public function events()
    {
        $sse = new SSE();
        $sse->addEventListener('event_projets_a', new ReserveEventHandler(getenv('WRIKE_PROJECT_A')));
        $sse->addEventListener('event_projets_b', new ReserveEventHandler(getenv('WRIKE_PROJECT_B')));
        return $sse->createResponse();
    }
}