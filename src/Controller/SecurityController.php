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
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    // Create UserDetails
                    $userDetails = new UserDetails();
                    $userDetails
                        ->setFirstName($form->get('firstName')->getData())
                        ->setLastName($form->get('lastName')->getData())
                        ->setAge($form->get('age')->getData())
                        ->setRegisterDate(new \DateTime())
                        ->setLastConnection(new \DateTime());

                    // Configure User
                    $user
                        ->setPassword($passwordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        ))
                        ->setToken(bin2hex(random_bytes(18)))
                        ->setRoles(['ROLE_USER'])
                        ->setUserDetails($userDetails);

                    $entityManager->persist($userDetails);
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // Auto-login after registration
                    return $security->login($user, 'form_login', 'main');

                } catch (\Exception $e) {
                    $this->addFlash('error', 'Registration failed. Please try again.');
                }
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

// Redirect if already logged in
//        if ($this->getUser()) {
//            return $this->redirectToRoute('app_home');
//        }
