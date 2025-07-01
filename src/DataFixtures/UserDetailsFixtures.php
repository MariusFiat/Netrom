<?php

namespace App\DataFixtures;

use App\Entity\UserDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserDetailsFixtures extends Fixture
{
    public const USER_COUNT = 20;
    private array $firstNames = [
        'John', 'Emma', 'Michael', 'Sophia', 'William', 'Olivia', 'James', 'Ava',
        'Robert', 'Mia', 'David', 'Charlotte', 'Richard', 'Amelia', 'Joseph', 'Harper',
        'Thomas', 'Evelyn', 'Charles', 'Abigail'
    ];

    private array $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
        'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $userDetails = new UserDetails();
            $userDetails->setFirstName($this->firstNames[$i]);
            $userDetails->setLastName($this->lastNames[$i]);
            $userDetails->setAge($faker->numberBetween(18, 80));
            $userDetails->setRegisterDate($faker->dateTimeBetween('-5 years', 'now'));
            $userDetails->setLastConnection($faker->dateTimeBetween('-1 month', 'now'));

            $manager->persist($userDetails);
            $this->addReference('user_details_'.$i, $userDetails);
        }

        $manager->flush();
    }
}
