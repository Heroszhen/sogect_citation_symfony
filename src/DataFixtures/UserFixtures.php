<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'aaaaaaaa'
        ));
        $user 
            ->setEmail("aaa@gmail.com")
            ->setName("Héros")
            ->setCreated(new \DateTime());
        $manager->persist($user);
        for($i = 0;$i < 10;$i++){
            $tab = explode(" ",$faker->name());
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'aaaaaaaa'
            ));
            $user 
                ->setEmail($faker->email())
                ->setName($tab[1])
                ->setCreated(new \DateTime());
            $manager->persist($user);
        }
        $manager->flush();
    }
}