<?php

namespace App\Controller\Tournament;

use App\Controller\AppController;
use App\Entity\Roster;
use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AppController
{

    #[Route('tournament/list', 'tournament.list')]
    public function list(TournamentRepository $tournamentRepository): Response
    {
        $tournaments = $tournamentRepository->findAll();

        return $this->render('tournament/list.html.twig', [
            'tournaments' => $tournaments
        ]);
    }

    #[Route('tournament/view/{id}', 'tournament.view')]
    public function view(Tournament $tournament): Response
    {
        $user = $this->getUser();

        return $this->render('tournament/view.html.twig', [
            'tournament' => $tournament,
            'user' => $user
        ]);
    }

    #[Route('tournament/signup/{id}', 'tournament.signup')]
    public function signup(Tournament $tournament, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $game = $tournament->getGame();
        $userTeam = $user->getOwnTeam();
        $roster = $userTeam->getRoster($game);

        if($tournament->hasRoster($roster)) {
            $tournament->removeRoster($roster);
            try {
                $entityManager->persist($tournament);
                $entityManager->flush();
                $this->addFlash('success', "Votre équipe n'est plus inscrite à ce tournoi.");
            } catch (Exception $_) {
                $this->addFlash('error', 'Une erreur est survenue...');
            }
            return $this->redirectToRoute('tournament.view', ['id' => $tournament->getId()]);
        }

        $errors = $this->checkSignupCriteria($tournament, $roster);

        if(!empty($errors)) {
            $errorMessage = "<ul>";
            foreach($errors as $error) {
                $errorMessage .= "<li>$error</li>";
            }
            $errorMessage .= "</ul>";
            $this->addFlash('error', sprintf("L'inscription a échouée :%s", $errorMessage));
            return $this->redirectToRoute('tournament.view', ['id' => $tournament->getId()]);
        }

        $tournament->addRoster($roster);
        try {
            $entityManager->persist($tournament);
            $entityManager->flush();
            $this->addFlash('success', 'Votre équipe est maintenant inscrite pour ce tournoi.');
        } catch (Exception $_) {
            $this->addFlash('error', 'Une erreur est survenue...');
        }

        return $this->redirectToRoute('tournament.view', ['id' => $tournament->getId()]);
    }

    private function checkSignupCriteria(Tournament $tournament, ?Roster $roster): array
    {
        $errors = [];

        if(!$roster) {
            $errors[] = 'Vous n\'avez pas de roster pour ce jeu.';
        }

        if($tournament->playersAmount()+$roster->getMembers()->count() >= $tournament->getMaxUsers()) {
            $errors[] = 'Ce tournoi est complet.';
        }

        foreach($roster->getMembers() as $member) {
            if($tournament->hasUser($member)) {
                $errors[] = sprintf("Le joueur %s est déjà inscrit.", $member);
            }
        }

        return $errors;
    }
}