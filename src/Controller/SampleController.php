<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sample")
 */
class SampleController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        return $this->render('dashboard/sample.html.twig');
    }
    
    /**
     * @Route("/events")
     */
    public function events()
    {
        return new Response('events');
    }
}