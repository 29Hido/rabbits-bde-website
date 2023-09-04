<?php

namespace App\Controller\Team;

use App\Controller\AppController;
use App\Entity\Game;
use App\Entity\Team;
use App\Form\CreateTeamType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

class TeamController extends AppController
{
    #[Route('/team/create', name: 'team.create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($user->getOwnTeam() != null){
            $this->addFlash('error', 'Vous avez déjà une team ! Supprimez la pour en créer une nouvelle.');
            return $this->redirectToAccount();
        }
        $team = new Team();
        $form = $this->createForm(CreateTeamType::class, $team);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $team->setCaptain($user);
            $user->addTeam($team);
            $team->setCreationDate(new \DateTimeImmutable());
            $entityManager->persist($team);
            $entityManager->flush();
            return $this->redirectToAccount();
        }

        return $this->render('team/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/team/view/{id}', name: 'team.view')]
    public function view(Team $team, ManagerRegistry $registry): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('team/view.html.twig', [
            'team' => $team,
            'user' => $this->getUser()
        ]);
    }

    #[Route('team/leave/{id}', 'team.leave')]
    public function leave(Team $team, ManagerRegistry $doctrine): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($user->isInTeam($team) && !$user->isCaptain($team)){
            $user->removeTeam($team);
            $doctrine->getManager()->flush();
        }
        return $this->redirectToAccount();
    }

    #[Route('team/delete/{id}', 'team.delete')]
    public function delete(Team $team, EntityManagerInterface $manager): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($user->isCaptain($team)){
            $teamRepository = $manager->getRepository(Team::class);
            $teamRepository->remove($team);
            $manager->flush();
        }
        return $this->redirectToAccount();
    }

    #[Route('team/list', 'team.list')]
    public function list(ManagerRegistry $doctrine): Response {
        $teams = $doctrine->getRepository(Team::class)->findAll();
        return $this->render('team/list.html.twig', [
            'teams' => $teams
        ]);
    }
}
