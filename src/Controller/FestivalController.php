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
}
