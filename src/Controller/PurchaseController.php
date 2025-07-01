<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PurchaseController extends AbstractController
{
    #[Route('/user/{id}/purchases', name: 'user_purchases')]
    public function index(User $user): Response
    {
        // Get all purchases for this user
        $purchases = $user->getPurchases();

        return $this->render('purchases.html.twig', [
            'user' => $user,
            'purchases' => $purchases,
        ]);
    }
}
