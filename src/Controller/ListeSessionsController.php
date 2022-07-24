<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeSessionsController extends AbstractController
{
    #[Route('/liste/sessions', name: 'app_liste_sessions')]
    public function index(): Response
    {
        return $this->render('liste_sessions/index.html.twig', [
            'controller_name' => 'ListeSessionsController',
        ]);
    }
}
