<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AppController
{
    public const WHITELISTED_DOMAIN = "univ-lille.fr";
    public const EMAIL_NAME_SEPARATOR = ".";
    public const EMAIL_SEPARATOR_COUNT = 3;

    #[Route('/login', name: 'login.index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToAccount();
        }

        return $this->render('login/index.html.twig', [
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'lastError' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout', name: 'login.logout', methods: ['GET'])]
    public function logout()
    {

    }

    #[Route('/register', name: 'login.register')]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToAccount();
        }
        $user = new User();
        $user->addRole("ROLE_USER");
        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->checkEmail($user->getEmail())) {
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Adresse email universitaire invalide.');
                return $this->redirectToRoute('login.register');
            }
            return $this->redirectToRoute('login.index');
        }

        return $this->render('login/register.html.twig', [
            'form' => $form
        ]);
    }

    private function checkEmail(string $email): bool
    {
        $exploded_mail = explode('@', $email, 2);
        $username = $exploded_mail[0];
        $domain = $exploded_mail[1];
        /*if ($domain !== LoginController::WHITELISTED_DOMAIN || substr_count($username, LoginController::EMAIL_NAME_SEPARATOR) !== LoginController::EMAIL_SEPARATOR_COUNT) {
            return false;
        }*/
        if (($domain !== LoginController::WHITELISTED_DOMAIN && $domain !== "fake.fr")) {
            return false;
        }

        return true;
    }
}
