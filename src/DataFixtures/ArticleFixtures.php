<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Background;
use App\Entity\User;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;

class ArticleFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'blogger');
        $user
            ->setRoles(['ROLE_BLOGGER', 'ROLE_USER'])
            ->setEmail('blog@blog.blog')
            ->setPassword($encodedPassword)
            ->setFirstName('Notartem')
            ->setLastName('Nottantsiura')
            ->setApiToken($uuid4 = Uuid::uuid4());
        
        for ($i = 0; $i < 7; $i++) {
            $article = new Article();
            $faker = \Faker\Factory::create();
            $article
                ->setTitle($faker->realText(50))
                ->setShortText($faker->realText(200))
                ->setLongText($faker->realText(900))
                ->setAuthor($user)
                ->setImage($faker->image($dir = 'public/assets/img', $width = 940, $height = 680, 'city', false));
            $tag = new Tag();
            $tag->setText($faker->word);
            $manager->persist($tag);
            $article->addTag($tag);
            
            $manager->persist($article);
        }

        $manager->flush();

//        for ($i = 0; $i < 5; $i++) {
//
//            $article = new Article();
//            $faker = \Faker\Factory::create();
//            $article
//                ->setTitle($faker->realText(50))
//                ->setShortText($faker->realText(200))
//                ->setLongText($faker->realText(900))
//                ->setAuthor('NOTArtem NOTTantsiura')
//                ->setAuthorId('1');
//            $manager->persist($article);
//        }
    }
}
