<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Form\EditionForm;
use App\Repository\EditionsRepository;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EditionsController extends AbstractController
{
    private int $ItemsPerPage = 2;

    #[Route('/festival/{id}/editions', name: 'festival_editions', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showEditions(
        int $id,
        EditionsRepository $editionsRepository,
        FestivalRepository $festivalRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $festival = $festivalRepository->find($id);

        $query = $editionsRepository->createQueryBuilder('e')
            ->innerJoin('e.festival_id', 'f')
            ->addSelect('f')
            ->where('f.id = :festivalId')
            ->setParameter('festivalId', $id)
            ->getQuery();

        $editions = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->ItemsPerPage
        );

        return $this->render('editions.html.twig', [
            'editions' => $editions,
            'festival' => $festival,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/festival/{festival_id}/edition/add', name: 'add_edition', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function addEdition(
        int $festival_id,
        Request $request,
        FestivalRepository $festivalRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $festival = $festivalRepository->find($festival_id);

        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        $edition = new Editions();
        $edition->setFestivalId($festival);

        $form = $this->createForm(EditionForm::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($edition);
            $em->flush();

            $this->addFlash('success', 'Edition created successfully!');
            return $this->redirectToRoute('festival_editions', ['id' => $festival_id]);
        }

        return $this->render('add_edition.html.twig', [
            'form' => $form->createView(),
            'festival' => $festival,
        ]);
    }

    #[Route('/edition/delete/{id}', name: 'delete_edition', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(
        int $id,
        EditionsRepository $editionsRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_edition'.$id, $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $edition = $editionsRepository->find($id);

        if (!$edition) {
            throw $this->createNotFoundException('Edition not found');
        }

        // Since Purchase doesn't relate directly to Edition, we just delete the edition
        $em->remove($edition);
        $em->flush();

        $this->addFlash('success', 'Edition deleted successfully!');

        return $this->redirectToRoute('festival_editions', [
            'id' => $edition->getFestivalId()->getId()
        ]);
    }

    #[Route('/edition/edit/{id}', name: 'edit_edition', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        int $id,
        Request $request,
        EditionsRepository $editionsRepository,
        EntityManagerInterface $em
    ): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $edition = $editionsRepository->find($id);

        if (!$edition) {
            throw $this->createNotFoundException('Edition not found');
        }

        $form = $this->createForm(EditionForm::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Edition updated successfully!');
            return $this->redirectToRoute('festival_editions', [
                'id' => $edition->getFestivalId()->getId()
            ]);
        }

        return $this->render('edit_edition.html.twig', [
            'form' => $form->createView(),
            'edition' => $edition,
            'festival' => $edition->getFestivalId(),
        ]);
    }
}
