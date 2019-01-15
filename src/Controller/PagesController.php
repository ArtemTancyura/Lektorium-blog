<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Background;
use App\Entity\Comments;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MessageService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentType;
use App\Entity\UserLike;
use App\Service\LikeServices;
use App\Entity\UserDislike;
use App\Service\DislikeServices;
use Symfony\Component\HttpFoundation\JsonResponse;

class PagesController extends Controller
{

    /**
     * List of articles
     */


    /**
     * @Route("/home", name="home")
     */
    public function homeAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $repository->findAll();
        $repository = $this->getDoctrine()->getRepository(Background::class);
        $bg = $repository->findOneBy([], ['id' => 'DESC']);
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articlesQuery = $repository->findBy([], ['id' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $articlesQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('base.html.twig', [
            'articles' => $articles,
            'tags' => $tag,
            'bg' => $bg
        ]);
    }


    /**
     * Full article logic
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/home/{id}", name="more")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moreAction(Request $request, Article $id, LikeServices $LikeServices, DislikeServices $dislikeServices)
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $repository->findAll();
        $articles = $id;
        $likes = $LikeServices->countLikes($articles);
        $dislikes = $dislikeServices->countDislikes($articles);
        $em = $this->getDoctrine()->getManager();
        // system control content quality :)
        // delete article if dislikes > likes 5 times
        if($dislikes > ($likes + 1) * 5){
            $article = $id;
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        $comments = new Comments();
        $articles->addComment($comments);
        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comments->setAuthor($this->getUser());
            $em->persist($comments);
            $em->flush();
            return $this->redirectToRoute('more', [
                'id' => $articles->getId()
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comments::class)
            ->findBy(['article' => $articles], ['id' => 'DESC']);

        return $this->render('article.html.twig', [
            'article' => $articles,
            'form' => $form->createView(),
            'comments' => $comments,
            'tags' => $tag,
            'likes' => $likes,
            'dislikes' => $dislikes
        ]);
    }


    /**
     * Likes logic
     */


    /**
     * @Route("/home/{id}/like", name="like")
     */
    public function likeAction(Article $article)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $like = $em->getRepository(UserLike::class)
            ->findOneBy([
                'user' => $user,
                'article' => $article
            ]);
        if (!$like) {
            $like = new UserLike();
            $like
                ->setArticle($article)
                ->setUser($user)
                ->setLikes(true);
            $em->persist($like);
            $em->flush();
        } else {
            $em->remove($like);
            $em->flush();
        }
        return $this->redirectToRoute('more', [
            'id' => $article->getId()
        ]);
    }

    /**
     * Dislikes logic
     */


    /**
     * @Route("/home/{id}/dislike", name="dislike")
     */
    public function dislikeAction(Article $article)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $dislike = $em->getRepository(UserDislike::class)
            ->findOneBy([
                'user' => $user,
                'article' => $article
            ]);
        if (!$dislike) {
            $dislike = new UserDislike();
            $dislike
                ->setArticle($article)
                ->setUser($user)
                ->setDislikes(true);
            $em->persist($dislike);
            $em->flush();
        } else {
            $em->remove($dislike);
            $em->flush();
        }
        return $this->redirectToRoute('more', [
            'id' => $article->getId()
        ]);
    }


    /**
     * Finding article order tag
     */


    /**
     * @Route("/home/tag/{id}", name="articles_order_tag")
     */
    public function articleOrderTagAction(Request $request, Tag $id)
    {
        $repository1 = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $repository1->findAll();
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $query = $repository->createQueryBuilder('u')
            ->innerJoin('u.tags', 'g')
            ->where('g.text= :tag_text')
            ->setParameter('tag_text', $id->getText())
            ->getQuery()->getResult();
        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('tagArticles.html.twig', [
            'articles' => $articles,
            'tags' => $tag
        ]);
    }
    

    /**
     * view author articles
     */


    /**
     * @Route("/home/author/{id}", name="view-author")
     */
    public function authorAction(Request $request, User $id)
    {
        $user = $id;
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articlesQuery = $repository->findBy(['author' => $id], ['id' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $articlesQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('author.html.twig', [
            'articles' => $articles,
            'user' => $user,
        ]);
    }


    /**
     * Full author article logic
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/home/author/article/{id}", name="author_more")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authorMoreAction(Request $request, Article $id, LikeServices $LikeServices, DislikeServices $dislikeServices)
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $repository->findAll();
        $articles = $id;
        $likes = $LikeServices->countLikes($articles);
        $dislikes = $dislikeServices->countDislikes($articles);
        $comments = new Comments();
        $articles->addComment($comments);
        $form = $this->createForm(CommentType::class, $comments);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comments->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comments);
            $em->flush();
            return $this->redirectToRoute('author_more', [
                'id' => $articles->getId()
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comments::class)
            ->findBy(['article' => $articles], ['id' => 'DESC']);
        
        return $this->render('authorArticle.html.twig', [
            'article' => $articles,
            'form' => $form->createView(),
            'comments' => $comments,
            'tags' => $tag,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}
