<?php

namespace App\Controller;

use App\EventHandler\ReserveEventHandler;
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
     * @Route("/events")
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function events()
    {
        $sse = new SSE();
        $projects = $this->getProjects();
        foreach ($projects as $project) {
            $sse->addEventListener("event_project_{$project}", new ReserveEventHandler($project));
        }
        
        return $sse->createResponse();
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
        curl_close($ch);
        $projects = json_decode($output, true)['data'][0]['childIds'];
        
        return $projects;
    }
}
