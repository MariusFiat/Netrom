<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use App\Entity\Purchase;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PurchaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Get all users, festivals, and tickets
        $users = $manager->getRepository(User::class)->findAll();
        $festivals = $manager->getRepository(Festival::class)->findAll();
        $tickets = $manager->getRepository(Ticket::class)->findAll();

        // Ensure we have enough entities to work with
        if (empty($users)) {
            throw new \RuntimeException('No users found. Run UserFixtures first.');
        }
        if (empty($festivals)) {
            throw new \RuntimeException('No festivals found. Run FestivalFixtures first.');
        }
        if (empty($tickets)) {
            throw new \RuntimeException('No tickets found. Run TicketFixtures first.');
        }

        // Create purchases for each user (2-5 purchases per user)
        foreach ($users as $user) {
            $purchaseCount = $faker->numberBetween(2, 5);

            for ($i = 0; $i < $purchaseCount; $i++) {
                $purchase = new Purchase();

                // Set random festival (avoid duplicates for the same user)
                $availableFestivals = array_filter($festivals, function($festival) use ($user) {
                    // Check if user already has a purchase for this festival
                    foreach ($user->getPurchases() as $existingPurchase) {
                        if ($existingPurchase->getFestivalId() === $festival) {
                            return false;
                        }
                    }
                    return true;
                });

                if (empty($availableFestivals)) {
                    continue; // Skip if user already has purchases for all festivals
                }

                $festival = $faker->randomElement($availableFestivals);
                $purchase->setFestivalId($festival);

                // Set random ticket type (weighted towards cheaper tickets)
                $ticket = $this->getWeightedRandomTicket($tickets);
                $purchase->setTicketTypeId($ticket);

                // Set user
                $purchase->setUserId($user);

                // Set is_used status (20% chance of being used)
                $purchase->setIsUsed($faker->boolean(20));

                // Set purchase date within festival date range (simulated)
                $manager->persist($purchase);
                $user->addPurchase($purchase);
            }
        }

        $manager->flush();
    }

    /**
     * Get a weighted random ticket (cheaper tickets are more likely)
     */
    private function getWeightedRandomTicket(array $tickets): Ticket
    {
        $weights = [];
        $totalPrice = array_sum(array_map(fn($t) => $t->getPrice(), $tickets));

        foreach ($tickets as $ticket) {
            // Higher weight for cheaper tickets (inverse of price)
            $weights[] = $totalPrice / $ticket->getPrice();
        }

        $random = mt_rand(1, (int)array_sum($weights));
        $current = 0;

        foreach ($tickets as $i => $ticket) {
            $current += $weights[$i];
            if ($random <= $current) {
                return $ticket;
            }
        }

        return $tickets[0]; // fallback
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            FestivalFixtures::class,
            TicketFixtures::class,
        ];
    }
}
