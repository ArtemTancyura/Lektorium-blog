<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 5; $i++) {
            
            $article = new Article();
            $faker = \Faker\Factory::create();
            $article
                ->setTitle($faker->realText(50))
                ->setShortText($faker->realText(200))
                ->setLongText($faker->realText(900))
                ->setAuthor($faker->name)
                ->setAuthorId('1');
            $manager->persist($article);
        }
        
        for ($i = 0; $i < 5; $i++) {

            $article = new Article();
            $faker = \Faker\Factory::create();
            $article
                ->setTitle($faker->realText(50))
                ->setShortText($faker->realText(200))
                ->setLongText($faker->realText(900))
                ->setAuthor('NOTArtem NOTTantsiura')
                ->setAuthorId('1');
            $manager->persist($article);
        }
        $manager->flush();

    }
}
