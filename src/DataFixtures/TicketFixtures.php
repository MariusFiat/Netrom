<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            ['VIP', 1300.00],
            ['Standard', 500.00],
            ['Early Bird', 350.00],
            ['Group (4 persons)', 1600.00],
            ['Student', 250.00],
            ['Weekend Pass', 800.00],
            ['Day Pass', 300.00],
            ['Premium VIP', 2000.00],
            ['Family Pack (2 adults + 2 kids)', 1800.00],
            ['Last Minute', 400.00]
        ];

        foreach ($types as $typeData) {
            $ticketType = new Ticket();
            $ticketType->setType($typeData[0]);
            $ticketType->setPrice($typeData[1]);
            $manager->persist($ticketType);
        }

        $manager->flush();
    }
}
