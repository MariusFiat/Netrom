<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageForm;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class StageController extends AbstractController
{
    #[Route('/stages', name: 'stage_list', methods: ['GET'])]
    public function index(
        StageRepository $stageRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $searchTerm = $request->query->get('q');

        $queryBuilder = $stageRepository->createQueryBuilder('s')
            ->orderBy('s.id', 'ASC');

        if ($searchTerm) {
            $queryBuilder
                ->where('s.name LIKE :searchTerm OR s.location LIKE :searchTerm OR s.description LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('stage_list.html.twig', [
            'stages' => $pagination,
            'searchTerm' => $searchTerm
        ]);
    }

    #[Route('/stages/add', name: 'add_stage', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $stage = new Stage();
        $form = $this->createForm(StageForm::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stage);
            $entityManager->flush();

            $this->addFlash('success', 'Stage created successfully!');
            return $this->redirectToRoute('stage_list');
        }

        return $this->render('add_stage.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
            'form_title' => 'Create New Stage'
        ]);
    }

    #[Route('stages/{id}/edit', name: 'stage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(StageForm::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Stage updated successfully!');
            return $this->redirectToRoute('stage_list');
        }

        return $this->render('edit_stage.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
            'form_title' => 'Edit Stage'
        ]);
    }

    #[Route('stages/{id}', name: 'stage_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Stage $stage,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $token = new CsrfToken('delete-stage', $request->request->get('_token'));

        if ($csrfTokenManager->isTokenValid($token)) {
            $entityManager->remove($stage);
            $entityManager->flush();
            $this->addFlash('success', 'Stage deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token');
        }

        return $this->redirectToRoute('stage_list');
    }

}
