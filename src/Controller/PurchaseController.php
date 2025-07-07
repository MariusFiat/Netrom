<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Purchase;
use App\Entity\User;
use App\Form\PurchaseForm;
use App\Repository\EditionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PurchaseController extends AbstractController
{
    #[Route('/user/{id}/purchases', name: 'user_purchases')]
    public function index(User $user): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        // Get all purchases for this user
        $purchases = $user->getPurchases();

        return $this->render('purchases.html.twig', [
            'user' => $user,
            'purchases' => $purchases,
        ]);
    }
    #[Route('/user/{id}/buy/{festival_id}', name: 'buy_ticket')]
    public function buyTicket(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request,
        #[MapEntity(id: 'festival_id')] ?Festival $festival
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$festival) {
            throw $this->createNotFoundException('Festival not found');
        }

        $purchase = new Purchase();
        $purchase->setFestivalId($festival);
        $purchase->setUserId($this->getUser());
        $purchase->setIsUsed(false);

        $form = $this->createForm(PurchaseForm::class, $purchase);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($purchase);
            $entityManager->flush();

            $this->addFlash('success', 'Ticket purchased successfully!');
            return $this->redirectToRoute('user_purchases', ['id' => $user->getId()]);
        }

        return $this->render('buy_ticket.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'festival' => $festival,
        ]);
    }

    #[Route('/purchase/delete/{id}', name: 'delete_purchase', methods: ['POST'])]
    public function delete(
        Purchase $purchase,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete'.$purchase->getId(), $request->request->get('_token'))) {
            $entityManager->remove($purchase);
            $entityManager->flush();

            $this->addFlash('success', 'Purchase was successfully deleted.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('user_purchases', ['id' => $this->getUser()->getId()]);
    }
}
