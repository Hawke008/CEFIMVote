<?php

namespace App\Controller;

use App\Entity\Votes;
use App\Form\VoteFormType;
use App\Repository\CandidatsRepository;
use App\Repository\ElecteursRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VotesController extends AbstractController
{
    // #[Route('/session/votes/{id}', name: 'app_votes')]
    #[Route('/electeur/vote', name: 'app_vote_un')]
    public function electeurVote(
        Request $request, 
        ElecteursRepository $electeursRepository, 
        CandidatsRepository $candidatsRepository,
        EntityManagerInterface $entityManager): Response
    {
        $electeurId=$request->query->get('electeurId');
        $electeur=$electeursRepository->find($electeurId);
        $sessionId=$request->query->get('sessionId');

        if ($request->isMethod('post')) {
            $voteUn=$request->request->get('voteUn');
            $voteUn=json_decode($voteUn);
       
            $vote=new Votes();
            $vote->setCandidat($candidatsRepository->find($voteUn));
            $vote->setElecteur($electeursRepository->find($electeurId));
            $vote->setTour(1);
            $entityManager->persist($vote);
            $entityManager->flush();
        }

        $candidats=$candidatsRepository->findBy(['session'=>$sessionId]);
        
        return $this->render('votes/vote.html.twig', [
            'electeurId'=>$electeurId, 
            'electeur'=>$electeur,
            'candidats'=>$candidats,
        ]);
    }

    #[Route('/vote/resultatvotes', name: 'app_resultat_votes')]
    public function affichageVotes(VotesRepository $votesRepository){
$resultat=$votesRepository->findBy(["candidat"=>27]);
dd($resultat);        

        return $this->render('votes/resultat.html.twig');
    }

}
