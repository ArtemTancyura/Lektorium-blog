<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $faker = \Faker\Factory::create();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user
            ->setRoles(['ROLE_USER'])
            ->setEmail('user@user.user')
            ->setPassword($encodedPassword)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setApiToken($uuid4 = Uuid::uuid4());
        $manager->persist($user);
        $manager->flush();
    }
}
