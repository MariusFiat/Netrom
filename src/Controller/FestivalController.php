<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Entity\Festival;
use App\Repository\EditionsRepository;
use App\Repository\FestivalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    #[Route('/festival', name: 'list_festivals', methods: ['GET'])]
    public function index(
        FestivalRepository $festivalRepository,  //* Obiect prin care am acces la datele din baza de date
        PaginatorInterface $paginator,
        Request $request

    ): Response {
        // Create query for all festivals
        $query = $festivalRepository->createQueryBuilder('f')->getQuery();  //* query-ul pentru selectia datelor

        // Paginate the query
        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            10 // Items per page
        ); //* variabila in care salvez datele din baza de date, dar si paginarea cu maxim 10 inregistrari pe pagina

        return $this->render('festival.html.twig', [
            'festivals' => $festivals,
        ]);
    }

    //Metoda de afisare editii atunci cand apas pe un festival
    #[Route('/festival/{id}/editions', name: 'festival_editions', methods: ['GET'])]
    public function showEditions(
        int $id,
        EditionsRepository $editionsRepository,
        FestivalRepository $festivalRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $festival = $festivalRepository->find($id);

        $query = $editionsRepository->createQueryBuilder('e')
            ->innerJoin('e.festival_id', 'f') // join Editions.festival as alias f
            ->addSelect('f')               // fetch festival data too (optional)
            ->where('f.id = :festivalId')
            ->setParameter('festivalId', $id)
            ->getQuery();

        $editions = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('editions.html.twig', [
            'editions' => $editions,
            'festival' => $festival, //Am nevoie pentru a scrie detaliile precum numele festivalului urmat de editii:
        ]);
    }
}
