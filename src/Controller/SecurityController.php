<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\RegisterFormType;
use App\Form\RegistrationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//        if ($this->getUser()) {
//            return $this->redirectToRoute('homepage');
//        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        // Redirect if already logged in
//        if ($this->getUser()) {
//            return $this->redirectToRoute('app_home');
//        }

        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Create and set UserDetails
                $userDetails = new UserDetails();
                $userDetails
                    ->setFirstName('')
                    ->setLastName('')
                    ->setAge(0)
                    ->setRegisterDate(new \DateTime())
                    ->setLastConnection(new \DateTime());

                $user
                    ->setPassword($passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    ))
                    ->setToken(bin2hex(random_bytes(18)))
                    ->setRoles(['ROLE_USER'])
                    ->setUserDetails($userDetails);

                // Persist both entities
                $entityManager->persist($userDetails);
                $entityManager->persist($user);
                $entityManager->flush();

                return $security->login($user, 'form_login', 'main');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Registration failed: '.$e->getMessage());
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
