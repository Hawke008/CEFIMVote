<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Entity\Electeurs;
use App\Entity\Sessions;
use App\Form\CreateSessionFormType;
use App\Repository\CandidatsRepository;
use App\Repository\ElecteursRepository;
use App\Repository\SessionsRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    #[Route('/session/dashboard/{id}/{state}', name: 'session_dashboard', methods: ['GET','POST'])]
    public function showDashboard(
        EntityManagerInterface $entityManager, 
        ElecteursRepository $electeurs, 
        SessionsRepository $session, 
        $id, 
        $state=0,
        CandidatsRepository $candidats,
        Request $request): Response

    {   $electeur = $electeurs->findAll();
        $sessionInfos = $session->find($id);
        $candidats=$candidats->findAll();


        // foreach($candidats as $candidat=>$electeurId){
        //     if($key%)
        //     dd($electeurId->getTitulaire());
        //     $candidatTitulaire=$electeurs->find($electeurId);
        //     var_dump($candidatTitulaire);
        // }
        
        $state=$state;
      
            if ($request->isMethod('post')) {
        
                $binome=$request->request->get('binome');
                $binome=json_decode($binome);
                
                foreach($binome as $key=>$value){
                    $candidats=new Candidats();
                   if(intval($key)%2==0){
                        $candidats->setTitulaire($electeurs->find($value));
                        $candidats->setSession($session->find($id));
                        $entityManager->persist($candidats);
                        $entityManager->flush();
                    }
                    if(intval($key)%2!=0){
                        
                        $candidats->setSuppleant($electeurs->find($value));
                        $candidats->setSession($session->find($id));
                        $entityManager->persist($candidats);
                        $entityManager->flush();
                    }
                }
                

            } 
        return $this->render('session/index.html.twig', ['electeurs' => $electeur, 'session' => $sessionInfos, 'state'=>$state,'candidats'=>$candidats]);
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
