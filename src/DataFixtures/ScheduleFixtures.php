<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Editions;
use App\Entity\Schedule;
use App\Entity\Stage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Get all artists, editions, and stages
        $artists = $this->getAllArtists();
        $editions = $this->getAllEditions();
        $stages = $this->getAllStages();

        foreach ($editions as $edition) {
            $editionDays = $this->getEditionDays($edition);
            $festivalStages = $this->getRandomStages($stages, rand(3, count($stages)));

            foreach ($editionDays as $day) {
                $dayStart = clone $day;
                $dayStart->setTime(14, 0); // Festival opens at 2 PM
                $dayEnd = clone $day;
                $dayEnd->setTime(23, 0);   // Main performances until 11 PM

                $artistsForDay = $this->getRandomArtists($artists, rand(8, 15));
                $timeSlots = $this->generateTimeSlots($dayStart, $dayEnd, count($artistsForDay));

                foreach ($artistsForDay as $i => $artist) {
                    $schedule = new Schedule();
                    $schedule->setEditionsId($edition);
                    $schedule->setArtistId($artist);
                    $schedule->setStageId($faker->randomElement($festivalStages));
                    $schedule->setStartTime($timeSlots[$i]['start']);
                    $schedule->setEndTime($timeSlots[$i]['end']);

                    $manager->persist($schedule);
                }
            }
        }

        $manager->flush();
    }

    private function getAllArtists(): array
    {
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

        return $artists;
    }

    private function getAllEditions(): array
    {
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

        return $editions;
    }

    private function getAllStages(): array
    {
        $stages = [];
        $stageNames = [
            'main_stage', 'dance_tent', 'rock_arena', 'urban_beats', 'sonic_forest',
            'global_rhythms', 'future_sound', 'acoustic_garden', 'neon_club', 'next_generation'
        ];

        foreach ($stageNames as $name) {
            $stages[] = $this->getReference('stage_'.$name, Stage::class);
        }

        return $stages;
    }

    private function getEditionDays(Editions $edition): array
    {
        $days = [];
        $currentDate = clone $edition->getStartDate();
        $endDate = clone $edition->getEndDate();

        while ($currentDate <= $endDate) {
            $days[] = clone $currentDate;
            $currentDate->modify('+1 day');
        }

        return $days;
    }

    private function getRandomArtists(array $artists, int $count): array
    {
        shuffle($artists);
        return array_slice($artists, 0, min($count, count($artists)));
    }

    private function getRandomStages(array $stages, int $count): array
    {
        shuffle($stages);
        return array_slice($stages, 0, min($count, count($stages)));
    }

    private function generateTimeSlots(\DateTime $dayStart, \DateTime $dayEnd, int $count): array
    {
        $slots = [];
        $totalMinutes = ($dayEnd->getTimestamp() - $dayStart->getTimestamp()) / 60;
        $avgDuration = floor($totalMinutes / $count);
        $currentTime = clone $dayStart;

        for ($i = 0; $i < $count; $i++) {
            $duration = rand(30, 90); // 30-90 minute performances
            $gap = rand(0, 30); // 0-30 minute gaps between acts

            $endTime = clone $currentTime;
            $endTime->modify("+$duration minutes");

            $slots[] = [
                'start' => clone $currentTime,
                'end' => $endTime
            ];

            $currentTime->modify("+".($duration + $gap)." minutes");
        }

        return $slots;
    }

    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
            EditionFixtures::class,
            StageFixtures::class,
        ];
    }
}
