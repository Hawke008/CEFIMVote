<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VotesController extends AbstractController
{
    // #[Route('/session/votes/{id}', name: 'app_votes')]
    public function index()
    {

        $response = "YELLLOW";
    
        // ... further modify the response or return it directly
    
        return $response;
    }
}
