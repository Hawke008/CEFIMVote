<?php

namespace App\Controller;

use App\Entity\Electeurs;
use App\Repository\SessionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ElecteurIdentificationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ElecteurController extends AbstractController
{
   
    #[Route('/electeur', name: 'electeur_login')]
    public function login(
        Request $request, 
        SessionsRepository $sessionsRepository, 
        ): Response
    {

        if ($request->isMethod('post')) {

            $codeSession = $request->request->get('code');
            $codeSession = str_replace(' ', '', $codeSession);

            $session=$request->getSession();
            $session->set('codeSession', $codeSession);

            if (!is_null($sessionsRepository->findOneByCode($codeSession))) {

                return $this->redirectToRoute('electeur_identification');

            } else {
                $this->addFlash('error', 'Votre code de session est invalide');
            }
        }
        return $this->render('electeur/login.html.twig');
    }


    #[Route('/electeur/identification', name: 'electeur_identification')]
    public function identification(
        Request $request, 
        SessionsRepository $sessionsRepository, 
        EntityManagerInterface $entityManager): Response
    {
        $sessionNavigateur=$request->getSession();
        $codeSession=$sessionNavigateur->get('codeSession');

        $electeur = new Electeurs();
        $form = $this->createForm(ElecteurIdentificationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $formdata = $form->getData();
            
            $electeur->setNom($formdata->getNom());
            $electeur->setPrenom($formdata->getPrenom());
            $electeur->setSignature($formdata->getSignature());
            $electeur->setSession($sessionsRepository->findOneByCode($codeSession));
            $entityManager->persist($electeur);
            $entityManager->flush();
            
            $electeurId=$electeur->getId();
            $sessionId=$electeur->getSession()->getId();

            return $this->redirectToRoute('app_vote_un', ['electeurId'=>$electeurId, 'sessionId'=>$sessionId]);
        }

        return $this->render('electeur/index.html.twig', [
            'electeurIdForm' => $form->createView(),
        ]);
    }

    
}
