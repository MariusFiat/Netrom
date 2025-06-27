<?php

namespace App\Controller;

use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Festival; // Vreau sa iau datele din festival?

final class FestivalController extends AbstractController
{
    #[Route('/festival', name: 'list_festivals', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $festivals = $em->getRepository(Festival::class)->findAll();
        //dd($festivals);
        return $this->render('index.html.twig', [
            'festivals' => $festivals,
        ]);
    }
}
