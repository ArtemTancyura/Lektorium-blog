<?php

namespace App\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class TagsToCollectionTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function transform($tags)
    {
        return $tags;
    }

    public function reverseTransform($tags)
    {
        $tagCollection = new ArrayCollection();

        $tagsRepository = $this->manager
            ->getRepository('App:Tag');

        foreach ($tags as $tag) {
            $tagInRepo = $tagsRepository->findOneByText($tag->getText());

            if ($tagInRepo !== null) {
                // Add tag from repository if found
                $tagCollection->add($tagInRepo);
            } else {
                // Otherwise add new tag
                $tagCollection->add($tag);
            }
        }

        return $tagCollection;
    }
}
