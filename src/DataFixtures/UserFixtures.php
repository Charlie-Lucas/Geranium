<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private Generator $faker;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
         $this->faker = Factory::create();
     }
    public function load(ObjectManager $manager)
    {
        $this->generateUser($manager, true);
        $this->generateUser($manager);
        $this->generateUser($manager);
        $this->generateUser($manager);
    }
    private function generateUser(ObjectManager $manager, $admin = false) {
        $user = new User();
        if($admin) {
            $user->setEmail('admin@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        } else {
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $this->faker->password));
        }
        $user->setFirstname($this->faker->firstName);
        $user->setUsername($this->faker->userName);
        $user->setLastname($this->faker->lastName);
        $manager->persist($user);

        $manager->flush();
    }
}
