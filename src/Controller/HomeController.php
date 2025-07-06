<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        // Get some statistics to display on the home page (optional)
        $stats = [
            'total_users' => 42,    // You would query your database here
            'total_festivals' => 7, // Example data
            'upcoming_events' => 3  // Example data
        ];

        return $this->render('home.html.twig', [
            'page_title' => 'Festival Management System',
            'stats' => $stats
        ]);
    }
}
