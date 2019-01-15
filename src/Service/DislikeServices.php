<?php
namespace App\Service;

use App\Entity\Article;

class DislikeServices
{
    public function countDislikes(Article $article): int
    {
        $countDislikes = $article->getUserDislikes();
        $allDislikes = count($countDislikes);
        foreach ($countDislikes as $dislike) {
            if ($dislike->getDislikes()==false) {
                $countDislikes--;
            }
        }
        return $allDislikes;
    }
}
