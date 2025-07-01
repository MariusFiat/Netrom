<?php

namespace App\DataFixtures;

use App\Entity\Editions;
use App\Entity\Festival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EditionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Get all 15 festivals from references
        $festivalReferences = [
            'festival_untold',
            'festival_electric_castle',
            'festival_neversea',
            'festival_summer_well',
            'festival_artmania',
            'festival_jazz_in_the_park',
            'festival_plai_festival',
            'festival_golden_stag_festival',
            'festival_saga_festival',
            'festival_rockstadt_extreme_fest',
            'festival_medieval_festival',
            'festival_george_enescu_festival',
            'festival_transilvania_international_film_festival',
            'festival_street_food_festival'
        ];

        foreach ($festivalReferences as $festivalRef) {
            /** @var Festival $festival */
            $festival = $this->getReference($festivalRef, Festival::class);

            // Each festival gets 1-4 editions (2019-2022)
            $editionCount = rand(1, 4);

            for ($i = 1; $i <= $editionCount; $i++) {
                $edition = new Editions();
                $edition->setFestivalId($festival);

                // Set dates based on festival type
                $year = 2019 + $i; // Editions from 2019-2022
                $month = $this->getFestivalMonth($festival->getName());

                $startDate = $faker->dateTimeBetween(
                    "$year-$month-01",
                    "$year-$month-20"
                );

                $endDate = clone $startDate;
                $endDate->modify('+'.rand(2,5).' days');

                $edition->setStartDate($startDate);
                $edition->setEndDate($endDate);

                $manager->persist($edition);

                // Create a unique reference for each edition
                $this->addReference(
                    'edition_'.$this->slugify($festival->getName()).'_'.$year,
                    $edition
                );
            }
        }

        $manager->flush();
    }

    private function getFestivalMonth(string $festivalName): string
    {
        // Assign realistic months based on actual festival schedules
        return match(true) {
            str_contains($festivalName, 'Untold') => '08', // August
            str_contains($festivalName, 'Electric Castle') => '07', // July
            str_contains($festivalName, 'Neversea') => '07', // July
            str_contains($festivalName, 'Summer Well') => '08', // August
            str_contains($festivalName, 'Medieval') => '07', // July
            str_contains($festivalName, 'George Enescu') => '09', // September
            str_contains($festivalName, 'Toamna') => '10', // October ("Toamna" means "Autumn")
            default => str_pad(rand(6,8), 2, '0', STR_PAD_LEFT) // Summer months for others
        };
    }

    private function slugify(string $text): string
    {
        return strtolower(str_replace([' ', 'ă', 'â', 'î', 'ș', 'ț', 'Ă', 'Â', 'Î', 'Ș', 'Ț'],
            ['_', 'a', 'a', 'i', 's', 't', 'a', 'a', 'i', 's', 't'],
            $text));
    }

    public function getDependencies(): array
    {
        return [
            FestivalFixtures::class,
        ];
    }
}
