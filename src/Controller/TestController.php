<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Festival; // Vreau sa iau datele din festival?

final class TestController extends AbstractController
{
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
