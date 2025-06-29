<?php

namespace App\Controller;

use App\Repository\EditionsRepository;
use App\Repository\FestivalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditionsController extends AbstractController
{
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
            3
        );

        return $this->render('editions.html.twig', [
            'editions' => $editions,
            'festival' => $festival, //Am nevoie pentru a scrie detaliile precum numele festivalului urmat de editii:
        ]);
    }
}
