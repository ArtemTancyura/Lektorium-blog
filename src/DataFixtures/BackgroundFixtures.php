<?php

namespace App\DataFixtures;

use App\Entity\Background;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BackgroundFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $bg = new Background();
        $bg
            ->setText('Enjoy my blog')
            ->setImage('77892475a745caa3a2fe21494cd534a9.jpeg');

        $manager->persist($bg);
        $manager->flush();
    }
}
