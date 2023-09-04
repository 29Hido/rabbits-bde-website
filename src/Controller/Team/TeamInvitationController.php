<?php

namespace App\Controller\Team;

use App\Controller\AppController;
use App\Entity\TeamInvitation;
use App\Entity\User;
use App\Form\TeamInvitationCreateType;
use App\Repository\TeamInvitationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamInvitationController extends AppController
{
    #[Route('/team/invitation/create', name: 'team.invitation.create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $team = $user->getOwnTeam();
        if($team == null) return $this->redirectToRoute('account.index');

        $invitation = new TeamInvitation();
        $invitation->setCreationDate(new \DateTimeImmutable());
        $invitation->setTeam($team);

        $form = $this->createForm(TeamInvitationCreateType::class, $invitation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $userRepository = $entityManager->getRepository(User::class);
            $invitedUser = $userRepository->findOneBy(['email' => $invitation->getUserEmail()]);
            if($invitedUser != null) {
                if(!$invitedUser->isInTeam($team)){
                    $invitation->setUser($invitedUser);
                    $entityManager->persist($invitation);
                    try {
                        $entityManager->flush();
                        $this->addFlash('success', "L'utilisateur a été invité.");
                    } catch(\Exception $e){
                        dd($e);
                        $this->addFlash('error', "L'utilisateur a déjà une invitation en cours.");
                    }
                } else {
                    $this->addFlash('error', "L'utilisateur est déjà dans l'équipe.");
                }
            } else {
                $this->addFlash('error', "L'utilisateur n'a pas été trouvé.");
            }
            return $this->redirectToAccount();
        }

        return $this->render('team_invitation/create.html.twig', [
            'team' => $team,
            'form' => $form
        ]);
    }

    #[Route('/team/invitation/accept/{id}', name: 'team.invitation.accept')]
    public function accept(TeamInvitation $teamInvitation, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($user === $teamInvitation->getUser()){
            $user->addTeam($teamInvitation->getTeam());
            $manager = $doctrine->getManager();
            $inviteRepo = $manager->getRepository(TeamInvitation::class);
            $inviteRepo->delete($teamInvitation);
            $manager->flush();
        }
        return $this->redirectToAccount();
    }

    #[Route('/team/invitation/decline/{id}', name: 'team.invitation.decline')]
    public function decline(TeamInvitation $teamInvitation, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($user === $teamInvitation->getUser()){
            $manager = $doctrine->getManager();
            $inviteRepo = $manager->getRepository(TeamInvitation::class);
            $inviteRepo->delete($teamInvitation);
        }
        return $this->redirectToAccount();
    }
}
