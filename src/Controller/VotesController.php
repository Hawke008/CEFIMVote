<?php

namespace App\Controller;

use App\Entity\Votes;
use App\Repository\CandidatsRepository;
use App\Repository\ElecteursRepository;
use App\Repository\SessionsRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VotesController extends AbstractController
{


  
    #[Route('/electeur/vote', name: 'app_vote')]
    public function electeurVote(
        Request $request, 
        ElecteursRepository $electeursRepository, 
        CandidatsRepository $candidatsRepository,
        SessionsRepository $sessionsRepository,
        VotesRepository $votesRepository,
        EntityManagerInterface $entityManager,
        HubInterface $hub): Response
    {

        $electeurId = '';

        if($request->getMethod() === "GET"){
            $electeurId = $request->query->get("electeurId");
        }
        else{
            $electeurId = $request->request->get('electeurId');
        }

        if(is_object($votesRepository->findOneByElecteur($electeurId))){
            $electeurVote = true;
        }
        else{
            $electeurVote = false ;
        }
        $electeur=$electeursRepository->find($electeurId);
        $sessionId= $_GET['sessionId'];

        $session = $sessionsRepository->find($sessionId);
        $state=$sessionsRepository->findOneById($sessionId)->getState();
        $tour=null;
        $candidats=[];

        if ($state==1){
            $candidats=$candidatsRepository->findBy(['session'=>$sessionId]);
            $tour=1;
        }
        if ($state==2){
            $tour=2;
            $voteResults =$votesRepository->affichageResultats();

            foreach($voteResults as $result){
                $candidats[]=$candidatsRepository->find($result[1]);
            }
        }

        if ($request->isMethod('post')) {
            $update = new Update(
                'test',
                json_encode($_POST['data'])
            );
            $hub->publish($update);
            $votes=$request->request->get('vote');
            $votes=json_decode($votes);
            $vote=new Votes();
            $vote->setCandidat($candidatsRepository->find($votes));
            $vote->setElecteur($electeursRepository->find($electeurId));
            $vote->setTour($tour);
            $entityManager->persist($vote);
            $entityManager->flush();
        }

        return $this->render('votes/vote.html.twig', [
            'electeurId'=>$electeurId, 
            'electeur'=>$electeur,
            'candidats'=>$candidats,
            'state'=>$state,
            'session' => $session,
            'electeurVote' => $electeurVote

        ]);
    }

    #[Route('/vote/resultatvotes', name: 'app_resultat_votes')]
    public function affichageVotes(VotesRepository $votesRepository, CandidatsRepository $candidats){

        return $this->render('votes/resultat.html.twig');
    }

    #[Route('/vote/resultat', name: 'app_affichage_resultat_votes')]
    public function affichageResultatsVote(VotesRepository $votesRepository, CandidatsRepository $candidats){

        // $tour=$votesRepository->findBy(array('tour'=>'2'));
        // $candidats=$candidats->find($tour);
        // dd($candidats);
        // $candidats=affichageCandidatsTest($tour, $session);
       $candidats=$candidats->findAll();
       $resultatByCandidatBinome=[];
        foreach ($candidats as $candidat){
            $candidatId=$candidat->getId();
            array_push($resultatByCandidatBinome,$votesRepository->findBy(['candidat'=>$candidatId]));
        }

        $resultatforEachCandidat=[];
        foreach ($resultatByCandidatBinome as $resultatBysingleCandidat){
            array_push($resultatforEachCandidat,count($resultatBysingleCandidat));
        }

        $conclusionVote="";
        $totalVotes=array_sum($resultatforEachCandidat);

        foreach($resultatforEachCandidat as $resultat){
            if($resultat<$totalVotes/2){
                $conclusionVote="Auncun candidat ne remporte la majorité";
            }
            else{
                $conclusionVote="La majorité est atteinte";
            }
        }


        return $this->render('votes/partials/_affichage_resultats.html.twig', [
            'candidats'=>$candidats,
            'resultatByBinome'=>$resultatforEachCandidat,
            'conclusionVote'=>$conclusionVote
        ]);
    }


}
