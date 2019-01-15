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
        $user1 = new User();        
        $encodedPassword1 = $this->passwordEncoder->encodePassword($user1, 'blogger');
        $user1
            ->setRoles(['ROLE_BLOGGER', 'ROLE_USER'])
            ->setEmail('blog@blog.blog')
            ->setPassword($encodedPassword1)
            ->setFirstName('Notartem')
            ->setLastName('Nottantsiura')
            ->setApiToken($uuid4 = Uuid::uuid4());        
        $manager->persist($user1);
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {
            $user2 = new User();
            $faker = \Faker\Factory::create();
            $encodedPassword2 = $this->passwordEncoder->encodePassword($user2, $faker->realText(10));
            $user2
                ->setRoles(['ROLE_USER'])
                ->setEmail($faker->email)
                ->setPassword($encodedPassword2)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setApiToken($uuid4 = Uuid::uuid4());
            $manager->persist($user2);
        }
        $manager->flush();
    }
}
