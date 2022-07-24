<?php

namespace App\Controller;
use App\Entity\Sessions;
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
    #[Route('/electeur/identification', name: 'app_electeur_identification')]
    public function electeurIdentification(Request $request, SessionsRepository $sessionsRepository,EntityManagerInterface $entityManager): Response
    {

        $electeur = new Electeurs();
        $form = $this->createForm( ElecteurIdentificationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formdata = $form->getData();
            $electeur->setNom($formdata->getNom());
            $electeur->setPrenom($formdata->getPrenom());
            $electeur->setSignature($formdata->getSignature());
            
            // $session=$sessionsRepository->getId();
            // dd($session);
            // dd($sessionId);
            // $electeur->setSession();
            $entityManager->persist($electeur);
            $entityManager->flush();
          
            return $this->redirectToRoute('app_electeur_vote_un');
        }
        return $this->render('electeur/index.html.twig', [
            'electeurIdForm' => $form->createView(),
        ]);
    }

    #[Route('/electeur/vote', name: 'app_electeur_vote_un')]
    public function electeurVote(): Response
    {


        return $this->render('electeur/vote.html.twig', [
            // 'electeurIdForm' => $form->createView(),
        ]);
    }
}
