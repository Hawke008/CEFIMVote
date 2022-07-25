<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://localhost/.well-known/mercure',
            json_encode(['test' => '1'])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}
