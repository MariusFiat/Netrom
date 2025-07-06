<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArtistTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArtistController extends AbstractController
{
    private int $itemsPerPage = 10;

    #[Route('/artists', name: 'artist_list', methods: ['GET'])]
    public function list(
        ArtistRepository $artistRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $searchTerm = $request->query->get('q');

        $queryBuilder = $artistRepository->createQueryBuilder('a');

        if ($searchTerm) {
            $queryBuilder
                ->where('a.name LIKE :searchTerm')
                ->orWhere('a.music_genre LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $artists = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            $this->itemsPerPage
        );

        return $this->render('artists_list.html.twig', [
            'artists' => $artists,
            'searchTerm' => $searchTerm
        ]);
    }

    #[Route('/artists/add', name: 'artist_add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $artist = new Artist();
        $form = $this->createForm(ArtistTypeForm::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artist);
            $entityManager->flush();

            $this->addFlash('success', 'Artist created successfully!');
            return $this->redirectToRoute('artist_list');
        }

        return $this->render('add_artist.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/artist/delete/{id}', name: 'artist_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(
        int $id,
        ArtistRepository $artistRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('Artist not found');
        }

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_artist'.$id, $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        // Remove artist from all editions first
        foreach ($artist->getEditions() as $edition) {
            $edition->removeArtist($artist);
        }

        $em->remove($artist);
        $em->flush();

        $this->addFlash('success', 'Artist deleted successfully');
        return $this->redirectToRoute('artist_list');
    }

    #[Route('/artist/edit/{id}', name: 'artist_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        Request $request,
        Artist $artist,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ArtistTypeForm::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Artist updated successfully!');
            return $this->redirectToRoute('artist_list');
        }

        return $this->render('edit_artist.html.twig', [
            'artist' => $artist,
            'form' => $form->createView(),
        ]);
    }
}
