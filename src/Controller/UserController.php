<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private int $itemsPerPage = 10;

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function list(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $searchTerm = $request->query->get('q');

        $queryBuilder = $userRepository->createQueryBuilder('u')
            ->leftJoin('u.userDetails', 'ud');

        if ($searchTerm) {
            $queryBuilder
                ->where('u.email LIKE :searchTerm')
                ->orWhere('ud.first_name LIKE :searchTerm')
                ->orWhere('ud.last_name LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $users = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            $this->itemsPerPage
        );

        return $this->render('user_list.html.twig', [
            'users' => $users,
            'searchTerm' => $searchTerm
        ]);
    }

    #[Route('/users/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('User_details.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$id, $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        if ($user->getUserDetails() !== null) {
            $em->remove($user->getUserDetails());
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle roles separately
            $selectedRole = $form->get('mainRole')->getData();
            $user->setRoles([$selectedRole]);

            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully!');
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('edit_user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
