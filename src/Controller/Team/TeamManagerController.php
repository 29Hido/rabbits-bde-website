<?php

namespace App\Controller\Team;

use App\Controller\AppController;
use App\Entity\TeamInvitation;
use App\Entity\User;
use App\Form\TeamInvitationCreateType;
use App\Form\TeamManagerGamesType;
use App\Repository\TeamInvitationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamManagerController extends AppController
{
    #[Route('/team/manage/games', name: 'team.manage.games')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $team = $user->getOwnTeam();
        if($team == null) return $this->redirectToRoute('account.index');
        $form = $this->createForm(TeamManagerGamesType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash('success', $team->getName() . " mis à jour avec succès.");
            return $this->redirectToRoute('team.view', ['id' => $team->getId()]);
        }
        return $this->render('team_manage/games.html.twig', [
            'team' => $team,
            'form' => $form
        ]);
    }
}
