<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_USER' => 15,  // 15 regular users
            'ROLE_ADMIN' => 3,  // 3 admins
            'ROLE_EDITOR' => 2   // 2 editors
        ];

        $roleAssignments = [];
        foreach ($roles as $role => $count) {
            for ($i = 0; $i < $count; $i++) {
                $roleAssignments[] = $role;
            }
        }
        shuffle($roleAssignments);

        $domains = ['example.com', 'test.org', 'domain.net', 'mail.io'];

        for ($i = 0; $i < UserDetailsFixtures::USER_COUNT; $i++) {
            $user = new User();
            $userDetails = $this->getReference('user_details_'.$i, UserDetails::class);

            $firstName = strtolower($userDetails->getFirstName());
            $lastName = strtolower($userDetails->getLastName());
            $domain = $domains[$i % count($domains)];

            $user->setEmail(sprintf('%s.%s@%s', $firstName, $lastName, $domain));
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'Password'.$i.'!')
            );
            $user->setToken(bin2hex(random_bytes(16)));
            $user->setRole($roleAssignments[$i]);
            $user->setUserDetails($userDetails);

            $manager->persist($user);

            // Store admin users as references
            if ($user->getRole() === 'ROLE_ADMIN') {
                $this->addReference('admin_'.$i, $user);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserDetailsFixtures::class,
        ];
    }
}
