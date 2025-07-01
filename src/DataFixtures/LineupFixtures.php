<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Editions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LineupFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Get all artists using the exact reference names from ArtistFixtures
        $artists = [];
        $artistNames = [
            'inna', 'edward_maya', 'alexandra_stan', 'smiley', 'carlas_dreams',
            'delia', 'the_motans', 'irina_rimes', 'feli', 'alternosfera',
            'subcarpati', 'om_la_luna', 'phoenix', 'tzanca_uraganu', 'nicole_cherry',
            'guess_who', 'cristi_mega', 'andra', 'holograf', 'vita_de_vie'
        ];

        foreach ($artistNames as $name) {
            $artists[] = $this->getReference('artist_'.$name, Artist::class);
        }

        // Get all editions
        $editions = [];
        $festivalNames = [
            'untold', 'electric_castle', 'neversea', 'summer_well', 'artmania',
            'jazz_in_the_park', 'plai_festival', 'golden_stag_festival', 'saga_festival',
            'rockstadt_extreme_fest', 'medieval_festival', 'george_enescu_festival',
            'transilvania_international_film_festival', 'street_food_festival',
            'toamna_muzicala_clujeana'
        ];

        foreach ($festivalNames as $festivalName) {
            for ($year = 2019; $year <= 2022; $year++) {
                $ref = 'edition_'.$festivalName.'_'.$year;
                if ($this->referenceRepository->hasReference($ref, Editions::class)) {
                    $editions[] = $this->getReference($ref, Editions::class);
                }
            }
        }

        // Create lineups (5-8 artists per edition)
        foreach ($editions as $edition) {
            $lineupSize = rand(5, min(8, count($artists)));
            $artistsForEdition = $this->getRandomArtists($artists, $lineupSize);

            foreach ($artistsForEdition as $artist) {
                $edition->addArtist($artist);
            }

            $manager->persist($edition);
        }

        $manager->flush();
    }

    private function getRandomArtists(array $artists, int $count): array
    {
        shuffle($artists);
        return array_slice($artists, 0, $count);
    }

    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
            EditionFixtures::class,
        ];
    }
}
