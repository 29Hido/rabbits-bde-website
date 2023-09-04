<?php

namespace App\Controller;

use App\Entity\TeamInvitation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account.index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $invitationsRepo = $doctrine->getManager()->getRepository(TeamInvitation::class);
        $invitations = $invitationsRepo->findBy(['user' => $user->getId()]);
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'invitations' => $invitations
        ]);
    }
}
