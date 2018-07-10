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
     *
     * @param Wrike $wrike
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
     * @param string $id
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
        $projects = $this->getProjects();

        return new JsonResponse($projects);
    }

    private function getProjects()
    {
        $wrikeUrl = getenv('WRIKE_URL');
        $folderId = getenv('WRIKE_PROJECTS_FOLDER_ID');
        $token = getenv('WRIKE_PERMANENT_TOKEN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "${wrikeUrl}/api/v3/folders/${folderId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            'Content-Type: application/json',
        ]);
        $output = curl_exec($ch);
        $projects = json_decode($output, true)['data'][0]['childIds'];
        curl_close($ch);
    
        return $projects;
    }
}
