<?php

namespace App\Controller;

use App\EventHandler\ReserveEventHandler;
use App\Service\Wrike;
use Sse\SSE;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projets")
 */
class ProjetsController extends AbstractController
{
    /**
     * @var Wrike
     */
    private $wrike;

    /**
     * ProjetsController constructor.
     */
    public function __construct(Wrike $wrike)
    {
        $this->wrike = $wrike;
    }

    /**
     * @Route("/events")
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    /*
    public function events()
    {
        $sse = new SSE();
        $projects = $this->getProjects();
        foreach ($projects as $project) {
            $sse->addEventListener("event_project_{$project}", new ReserveEventHandler($project, $this->wrike));
        }

        return $sse->createResponse();
    }
    */

    /**
     * @Route("/{id}/metrics")
     *
     * @return JsonResponse
     */
    public function projectMetrics(string $id)
    {
        return new JsonResponse($this->wrike->getFolderMetrics($id));
    }

    /**
     * @return JsonResponse
     * @Route("/list")
     */
    public function projets()
    {
        $projects = $this->wrike->getProjects();

        return new JsonResponse($projects);
    }
}
