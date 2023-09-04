<?php

namespace App\Controller\Team;

use App\Entity\Game;
use App\Entity\Roster;
use App\Entity\Team;
use App\Form\CreateRosterType;
use App\Controller\AppController;
use App\Repository\RosterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamRosterController extends AppController
{
    #[Route('/team/roster/create/{id}', name: 'team.roster.create')]
    public function view(Team $team, ManagerRegistry $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($team->getCaptain() !== $user) {
            return $this->redirectToAccount();
        }

        $roster = new Roster();
        $roster->setTeam($team);
        $form = $this->createForm(CreateRosterType::class, $roster);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game = $roster->getGame();

            if(!$this->respectCriteria($team, $roster, $game)) {
                return $this->redirectToRoute('team.roster.create', ['id' => $team->getId()]);
            }

            $entityManager = $doctrine->getManager();

            $actualRoster = $team->getRoster($game);
            if($actualRoster) {
                $actualRoster->setMembers($roster->getMembers());
                $entityManager->persist($actualRoster);
                $this->addFlash('info', sprintf('Roster %s édité avec succès !', $game));
            } else {
                $entityManager->persist($roster);
                $this->addFlash('success', sprintf('Roster %s créé avec succès !', $game));
            }

            $entityManager->flush();
            return $this->redirectToRoute('team.view', ['id' => $team->getId()]);
        }

        return $this->render('team/roster/create.html.twig', [
            'team' => $team,
            'form' => $form
        ]);
    }

    private function respectCriteria(Team $team, Roster $roster, Game $game) : bool
    {
        if($roster->getMembers()->isEmpty() || $roster->getMembers()->count() > $game->getRosterSize()) {
            $this->addFlash('error', sprintf('Vous devez ajouter entre 1 et %d membres au roster %s', $game->getRosterSize(), $game->getName()));
            return false;
        }

        return true;
    }
}