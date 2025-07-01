<?php

namespace App\DataFixtures;

use App\Entity\Stage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $stages = [
            [
                'name' => 'Main Stage',
                'location' => 'Central Arena',
                'description' => 'The primary stage hosting headliner acts with massive production'
            ],
            [
                'name' => 'Dance Tent',
                'location' => 'East Field',
                'description' => 'Electronic dance music stage with state-of-the-art sound system'
            ],
            [
                'name' => 'Rock Arena',
                'location' => 'North Zone',
                'description' => 'Dedicated stage for rock and metal performances'
            ],
            [
                'name' => 'Urban Beats',
                'location' => 'West Pavilion',
                'description' => 'Hip-hop, rap and urban music stage'
            ],
            [
                'name' => 'Sonic Forest',
                'location' => 'Woodland Area',
                'description' => 'Intimate stage surrounded by nature for alternative acts'
            ],
            [
                'name' => 'Global Rhythms',
                'location' => 'Cultural Village',
                'description' => 'World music and traditional performances stage'
            ],
            [
                'name' => 'Future Sound',
                'location' => 'Tech Dome',
                'description' => 'Experimental electronic and avant-garde music'
            ],
            [
                'name' => 'Acoustic Garden',
                'location' => 'South Lawn',
                'description' => 'Unplugged performances in a relaxed setting'
            ],
            [
                'name' => 'Neon Club',
                'location' => 'Underground Bunker',
                'description' => 'Late-night DJ sets and club atmosphere'
            ],
            [
                'name' => 'Next Generation',
                'location' => 'Discovery Zone',
                'description' => 'Stage dedicated to emerging artists and new talent'
            ]
        ];

        foreach ($stages as $stageData) {
            $stage = new Stage();
            $stage->setName($stageData['name']);
            $stage->setLocation($stageData['location']);
            $stage->setDescription($stageData['description']);

            $manager->persist($stage);

            // Add reference if you need to relate to other entities
            $this->addReference('stage_'.strtolower(str_replace(' ', '_', $stageData['name'])), $stage);
        }

        $manager->flush();
    }
}
