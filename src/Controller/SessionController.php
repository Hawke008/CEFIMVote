<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Entity\Sessions;
use App\Form\CreateSessionFormType;
use App\Repository\CandidatsRepository;
use App\Repository\ElecteursRepository;
use App\Repository\SessionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SessionController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    // affichageVotes(VotesRepository $votesRepository, CandidatsRepository $candidats, $admin=)


    #[Route('/session/dashboard/{id}', name: 'session_dashboard', methods: ['GET','POST'])]
    public function showDashboard(
        EntityManagerInterface $entityManager, 
        ElecteursRepository $electeurs, 
        SessionsRepository $session, 
        $id,
        CandidatsRepository $candidats,
        Request $request): Response

    {
        $electeurSession=$electeurs->findBy(["session"=>$id]);
        $candidats=$candidats->findBy(["session"=>$id]);
        $sessionInfos = $session->find($id);
  
        $state= $sessionInfos->getState();
        
        if ($request->isMethod('post')) {
            // $resultat=$request->request->get('resultat');
         
            $state=$request->request->get("state");

            if($state==1){

                $binomePost=$request->request->get('binome');
                $binome=json_decode($binomePost);

                foreach($binome as $key=>$value){
                    $candidats=new Candidats();
                    if(intval($key)%2==0){
                        $candidats->setTitulaire($electeurs->find($value));
                        $candidats->setSuppleant($electeurs->find($binome[$key+1]));
                        $candidats->setSession($session->find($id));
                        $entityManager->persist($candidats);
                        $entityManager->flush();
                    }
                    $this->updateState($entityManager,$sessionInfos->getId(),$state);
                }
            }
            if($state==2){
                $this->updateState($entityManager,$sessionInfos->getId(),$state);

            ;}
            // else if ($resultat){
                
            //     $resultat=json_decode($resultat);
                
            //     }
                
        }
    
        // $this->fetchSubscribers($sessionInfos->getCodeSession());
        return $this->render('session/index.html.twig', ['electeurs' => $electeurSession, 'session' => $sessionInfos, 'state'=>$state,'candidats'=>$candidats,]);
    }

    public function updateState(EntityManagerInterface $entityManager,int $id, int $state): Response
    {
        $session = $entityManager->getRepository(Sessions::class)->find($id);
        $session->setState($state);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('session_dashboard', [
            'id' => $session->getId()
        ]);
    }

    public function fetchSubscribers(String $topic): array
    {
        $response = $this->client->request(
            'GET',
           'http://localhost:3000/.well-known/subscriptions/'.$topic,
            [
                'auth_bearer' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOltdfX0.Oo0yg7y4yMa1vr_bziltxuTCqb8JVHKxp-f_FwwOim0',
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        if ($statusCode === 200){
            $content = $response->toArray();
        }
        dd($response);

        return $content;
    }

    public function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    #[Route('/session/new', name: 'session_creation')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Sessions();
        $form = $this->createForm(CreateSessionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formdata = $form->getData();
            $session->setHeureDebut(
                new \DateTime()
            );

            $session->setPromotion($formdata->getPromotion());
            $session->setDateDebutPromo($formdata->getDateDebutPromo());
            $session->setDateFinPromo($formdata->getDateFinPromo());
            $session->setVille($formdata->getVille());
            $session->setResponsable($formdata->getResponsable());
            $session->setState(0);
            $session->setCodeSession($this->generateRandomString());
            $entityManager->persist($session);
            $entityManager->flush();
            $sessionId = ($session->getId());

            return $this->redirectToRoute('session_dashboard', array('id' => $sessionId));
        }
        return $this->render('session/creation_session.html.twig', [
            'createSessionForm' => $form->createView(),
        ]);
    }

    
}
