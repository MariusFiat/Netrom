<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $romanianArtists = [
            ['name' => 'INNA', 'music_genre' => 'Dance/Pop'],
            ['name' => 'Edward Maya', 'music_genre' => 'House/Eurodance'],
            ['name' => 'Alexandra Stan', 'music_genre' => 'Dance/Pop'],
            ['name' => 'Smiley', 'music_genre' => 'Pop/Hip-Hop'],
            ['name' => 'Carla\'s Dreams', 'music_genre' => 'Alternative/Pop'],
            ['name' => 'Delia', 'music_genre' => 'Pop/R&B'],
            ['name' => 'The Motans', 'music_genre' => 'Pop/Rock'],
            ['name' => 'Irina Rimes', 'music_genre' => 'Pop'],
            ['name' => 'Feli', 'music_genre' => 'Pop'],
            ['name' => 'Alternosfera', 'music_genre' => 'Alternative Rock'],
            ['name' => 'Subcarpați', 'music_genre' => 'Hip-Hop/Electronic'],
            ['name' => 'Om la Lună', 'music_genre' => 'Indie Rock'],
            ['name' => 'Phoenix', 'music_genre' => 'Rock'],
            ['name' => 'Tzanca Uraganu', 'music_genre' => 'Manele'],
            ['name' => 'Nicole Cherry', 'music_genre' => 'Pop/Dance'],
            ['name' => 'Guess Who', 'music_genre' => 'Trap/Hip-Hop'],
            ['name' => 'Cristi Mega', 'music_genre' => 'Manele/Pop'],
            ['name' => 'Andra', 'music_genre' => 'Pop'],
            ['name' => 'Holograf', 'music_genre' => 'Rock'],
            ['name' => 'Vita de Vie', 'music_genre' => 'Alternative Rock']
        ];

        foreach ($romanianArtists as $artistData) {
            $artist = new Artist();
            $artist->setName($artistData['name']);
            $artist->setMusicGenre($artistData['music_genre']);

            $manager->persist($artist);

            // Create reference with normalized name (no special characters)
            $referenceName = $this->normalizeName($artistData['name']);
            $this->addReference('artist_'.$referenceName, $artist);
        }

        $manager->flush();
    }

    private function normalizeName(string $name): string
    {
        // Replace Romanian diacritics
        $replacements = [
            'ț' => 't', 'ș' => 's', 'ă' => 'a', 'â' => 'a', 'î' => 'i',
            'Ă' => 'a', 'Â' => 'a', 'Î' => 'i', 'Ș' => 's', 'Ț' => 't',
        ];

        // Convert to lowercase and replace special characters
        $normalized = strtr(mb_strtolower($name), $replacements);

        // Replace spaces with underscores and remove apostrophes
        $normalized = str_replace([' ', "'"], ['_', ''], $normalized);

        // Remove any remaining special characters
        return preg_replace('/[^a-z0-9_]/', '', $normalized);
    }
}
