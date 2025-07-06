<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Entity\Festival;
use App\Entity\Purchase;
use App\Form\FestivalForm;
use App\Repository\EditionsRepository;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    private int $ItemsPerPage = 10;
    #[Route('/festival', name: 'list_festivals', methods: ['GET'])]
    public function index(
        FestivalRepository $festivalRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $query = $festivalRepository->createQueryBuilder('f');

        // Add search functionality
        if ($searchTerm = $request->query->get('q')) {
            $query
                ->where('f.name LIKE :searchTerm OR f.location LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $query = $query->getQuery();

        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->ItemsPerPage
        );

        return $this->render('festival.html.twig', [
            'festivals' => $festivals,
        ]);
    }

    //Metoda de delete
    #[Route('/festival/delete/{id}', name: 'delete_festival')]
    public function delete(
        int $id,
        FestivalRepository $festivalRepository,
        EditionsRepository $editionsRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_festival'.$id, $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $festival = $festivalRepository->find($id);

        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        // First delete all purchases associated with this festival
        $purchases = $em->getRepository(Purchase::class)->findBy(['festival_id' => $festival]);
        foreach ($purchases as $purchase) {
            $em->remove($purchase);
        }

        // Then delete all editions associated with this festival
        $editions = $editionsRepository->findBy(['festival_id' => $festival]);
        foreach ($editions as $edition) {
            $em->remove($edition);
        }

        // Finally delete the festival
        $em->remove($festival);
        $em->flush();

        $this->addFlash('success', 'Festival deleted successfully');
        return $this->redirectToRoute('list_festivals');
    }

    //* Create form
    #[Route('/festival/add', name: 'add_festival')]
    public function new(Request $request, EntityManagerInterface $em,): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $newFestival = new Festival();

        $form = $this->createForm(FestivalForm::class, $newFestival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newFestival);
            $em->flush();

            $this->addFlash('success', 'Festival created successfully!');
            return $this->redirectToRoute('list_festivals');
        }

        return $this->render('add_festival.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    Edit method:
    #[Route('/festival/{id}/edit', name: 'edit_festival', methods: ['GET', 'POST'])]
    public function edit(Request $request, Festival $festival, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(FestivalForm::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Festival updated successfully!');
            return $this->redirectToRoute('list_festivals', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('edit_festival.html.twig', [
            'festival' => $festival,
            'form' => $form->createView(),
        ]);
    }
}
