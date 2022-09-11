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
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SessionController extends AbstractController
{

    // Affichage du dashboard en fonctiond des différents états : 1 & 2 (au 11/09/22)
    #[Route('/session/dashboard/{id}', name: 'session_dashboard', methods: ['GET', 'POST'])]
    public function showDashboard(
        EntityManagerInterface $entityManager,
        ElecteursRepository $electeurs,
        SessionsRepository $session,
        $id,
        CandidatsRepository $candidats,
        Request $request,
        HubInterface $hub
    ): Response {

        // Récupération des informations sur la session et des données sur les électeurs connectés, en cas de raffraichissement de la page (non persistence des données affichées par Mercure) 
        $electeurSession = $electeurs->findBy(["session" => $id]); 
        $candidats = $candidats->findBy(["session" => $id]);
        $sessionInfos = $session->find($id);
        $state = $sessionInfos->getState();

        //Réception des données postées en Ajax : state 1
        if ($request->isMethod('post')) {
            $state = $request->request->get("state");

            // State 1 Ajax : sauvegarde en base de données de la liste des candidats
            if ($state == 1) {
                $binomePost = $request->request->get('binome');
                $binome = json_decode($binomePost);

                foreach ($binome as $key => $value) {
                    $candidats = new Candidats();
                    if (intval($key) % 2 == 0) {
                        $candidats->setTitulaire($electeurs->find($value));
                        $candidats->setSuppleant($electeurs->find($binome[$key + 1]));
                        $candidats->setSession($session->find($id));
                        $entityManager->persist($candidats);
                        $entityManager->flush();
                    }
                    //Actualisation du state et MAJ et de Mercure
                    $this->updateState($entityManager, $sessionInfos->getId(), $state, $hub);
                }
            }
            // State 2
            if ($state == 2) {
                $this->updateState($entityManager, $sessionInfos->getId(), $state, $hub);
            }
        }
        return $this->render('session/index.html.twig', [
            'electeurs' => $electeurSession,
            'session' => $sessionInfos,
            'state' => $state,
            'candidats' => $candidats,
        ]);
    }


    // Récupération des électeurs pour injection dans le menu 'select candidats'
    #[Route('/session/dashboard/candidats/{id}', name: 'session_select_candidats')]
    public function getCandidats(ElecteursRepository $electeurs, $id, SerializerInterface $serializer)
    {
        $electeurSession = $electeurs->findBySession($id); 
        $electeurSession=$serializer->serialize($electeurSession, 'json', ['groups' => ['electeurs']]);
        return new Response($electeurSession);
    
    }

    // Envoie des informations vers MERCURE et mise à jour du state
    public function updateState(EntityManagerInterface $entityManager, int $id, int $state, HubInterface $hub): Response
    {
        $session = $entityManager->getRepository(Sessions::class)->find($id);

        $update = new Update(
            "canalElecteur",
            'done'
        );
        $hub->publish($update);

        $session->setState($state);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('session_dashboard', [
            'id' => $session->getId()
        ]);
    }

    // Création d'une nouvelle session
    #[Route('/session/new', name: 'session_creation')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Sessions();
        $form = $this->createForm(CreateSessionFormType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $formdata = $form->getData();
            $session->setHeureDebut(
                new \DateTime()
            );

            // $session->setPromotion($formdata->getPromotion());
            // $session->setDateDebutPromo($formdata->getDateDebutPromo());
            // $session->setDateFinPromo($formdata->getDateFinPromo());
            // $session->setVille($formdata->getVille());
            // $session->setResponsable($formdata->getResponsable());
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


    // Génération du code d'authenfication à transmettre à l'électeur pour entrer dans la session
    public function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
