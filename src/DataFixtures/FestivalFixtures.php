<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FestivalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $romanianFestivals = [
            ['name' => 'Untold', 'location' => 'Cluj-Napoca'],
            ['name' => 'Electric Castle', 'location' => 'Bontida'],
            ['name' => 'Neversea', 'location' => 'Constanta'],
            ['name' => 'Summer Well', 'location' => 'Buftea'],
            ['name' => 'ARTmania', 'location' => 'Sibiu'],
            ['name' => 'Jazz in the Park', 'location' => 'Cluj-Napoca'],
            ['name' => 'Plai Festival', 'location' => 'Timișoara'],
            ['name' => 'Golden Stag Festival', 'location' => 'Brașov'],
            ['name' => 'SAGA Festival', 'location' => 'București'],
            ['name' => 'Rockstadt Extreme Fest', 'location' => 'Râșnov'],
            ['name' => 'Medieval Festival', 'location' => 'Sighișoara'],
            ['name' => 'George Enescu Festival', 'location' => 'București'],
            ['name' => 'Transilvania International Film Festival', 'location' => 'Cluj-Napoca'],
            ['name' => 'Street Food Festival', 'location' => 'Multiple cities'],
            ['name' => 'Toamna Muzicală Clujeană', 'location' => 'Cluj-Napoca']
        ];

        foreach ($romanianFestivals as $festivalData) {
            $festival = new Festival();
            $festival->setName($festivalData['name']);
            $festival->setLocation($festivalData['location']);

            $manager->persist($festival);

            // Add reference if you need to relate to other entities
            $this->addReference('festival_'.strtolower(str_replace(' ', '_', $festivalData['name'])), $festival);
        }

        $manager->flush();
    }
}
