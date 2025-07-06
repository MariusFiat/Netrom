<?php

namespace App\Controller;

use App\Entity\Editions;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScheduleController extends AbstractController
{
    #[Route('/edition/{id}/schedule', name: 'edition_schedule')]
    public function index(Editions $edition, ScheduleRepository $scheduleRepository): Response
    {
        if (!$this->getUser()) { //block any access without login
            return $this->redirectToRoute('app_login');
        }

        // Get all schedules for this edition, ordered by start time
        $schedules = $scheduleRepository->findBy(
            ['editions_id' => $edition],
            ['start_time' => 'ASC']
        );

        // Get the lineup (artists) for this edition
        $lineup = $edition->getLineup();

        return $this->render('schedule.html.twig', [
            'edition' => $edition,
            'schedules' => $schedules,
            'lineup' => $lineup,
        ]);
    }
}
