<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Entity\Festival;
use App\Entity\Purchase;
use App\Repository\EditionsRepository;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    private int $ItemsPerPage = 10;
    #[Route('/festival', name: 'list_festivals', methods: ['GET'])]
    public function index(
        FestivalRepository $festivalRepository,  //* Obiect prin care am acces la datele din baza de date
        PaginatorInterface $paginator,
        Request $request

    ): Response {
        $query = $festivalRepository->createQueryBuilder('f')->getQuery();  //* query-ul pentru selectia datelor

        // Paginate the query
        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Mereu la apelul metodei, saltul se face la prima pagina
            $this->ItemsPerPage
        ); //* variabila in care salvez datele din baza de date, dar si paginarea cu maxim 10 inregistrari pe pagina

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
}
