<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Entity\Roster;
use App\Entity\Team;
use App\Entity\TeamInvitation;
use App\Entity\Tournament;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('RabbitsTournament Admin')
            ->setLocales(['fr']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Comptes', 'fa fa-user', User::class);

        yield MenuItem::section('Equipes');
        yield MenuItem::linkToCrud('Equipes', 'fa fa-users', Team::class);
        yield MenuItem::linkToCrud('Invitations', 'fa fa-envelope', TeamInvitation::class);
        //yield MenuItem::linkToCrud('Rosters', 'fa fa-home', Roster::class);

        yield MenuItem::section('Tournois');
        yield MenuItem::linkToCrud('Jeux', 'fa fa-gamepad', Game::class);
        yield MenuItem::linkToCrud('Tournois', 'fa fa-trophy', Tournament::class);
    }
}
