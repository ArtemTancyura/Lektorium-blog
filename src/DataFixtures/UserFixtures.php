<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'qwerty');

        $user
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setEmail('qwe@qwe.qwe')
            ->setPassword($encodedPassword)
            ->setFirstName('Artem')
            ->setLastName('Tantsiura');

        $manager->persist($user);
        $manager->flush();
        
        
        $user1 = new User();
        
        $encodedPassword1 = $this->passwordEncoder->encodePassword($user, 'blogger');
        $user1
            ->setRoles(['ROLE_BLOGGER', 'ROLE_USER'])
            ->setEmail('blog@blog.blog')
            ->setPassword($encodedPassword1)
            ->setFirstName('NOTArtem')
            ->setLastName('NOTTantsiura');

        $manager->persist($user1);
        $manager->flush();
    }
}
