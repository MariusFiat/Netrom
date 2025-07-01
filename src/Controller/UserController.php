<?php

namespace App\Controller;

use App\Entity\User;
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
        $query = $userRepository->createQueryBuilder('u')
            ->getQuery();

        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->itemsPerPage
        );

        return $this->render('user_list.html.twig', [
            'users' => $users,
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

        // Verify CSRF token
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$id, $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        // First remove the user details if they exist
        if ($user->getUserDetails() !== null) {
            $em->remove($user->getUserDetails());
        }

        // Then remove the user
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('user_list');
    }
}
