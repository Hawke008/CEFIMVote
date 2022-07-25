<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{ 
    protected int $test =0;

    #[Route('/publish/test', name: 'test_publish')]
    public function publish(HubInterface $hub): Response
    {
        if(isset($_POST['data'])){
            $this->test +=1;
            $update = new Update(
                'test',
                json_encode($_POST['data'])
            );
            $hub->publish($update);
        }

        return new Response('published!');
    }
    
    #[Route('/test', name: 'test_index')]
    public function index(): Response
    {

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'test' => $this->test,
        ]);
    }

}
