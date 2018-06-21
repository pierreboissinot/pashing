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
        $sse->addEventListener('event_projets_c', new ReserveEventHandler(getenv('WRIKE_PROJECT_C')));
        $sse->addEventListener('event_projets_d', new ReserveEventHandler(getenv('WRIKE_PROJECT_D')));
        $sse->addEventListener('event_projets_e', new ReserveEventHandler(getenv('WRIKE_PROJECT_E')));
        $sse->addEventListener('event_projets_f', new ReserveEventHandler(getenv('WRIKE_PROJECT_F')));
        $sse->addEventListener('event_projets_g', new ReserveEventHandler(getenv('WRIKE_PROJECT_G')));
        $sse->addEventListener('event_projets_h', new ReserveEventHandler(getenv('WRIKE_PROJECT_H')));
        $sse->addEventListener('event_projets_i', new ReserveEventHandler(getenv('WRIKE_PROJECT_I')));
        $sse->addEventListener('event_projets_j', new ReserveEventHandler(getenv('WRIKE_PROJECT_J')));
        return $sse->createResponse();
    }
}