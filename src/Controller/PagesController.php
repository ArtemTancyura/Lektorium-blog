<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use App\Entity\Like;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MessageService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentType;

class PagesController extends Controller
{

    /**
     * @Route("/home", name="home")
     */
    public function homeAction(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articlesQuery = $repository->findAll();

        $firstName = $this->getUser()->getFirstName();

        $lastName = $this->getUser()->getLastName();

        $user = $firstName . ' ' . $lastName;


        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $articlesQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('base.html.twig', [
            'articles' => $articles,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param Article $id
     * @Route("/home/like{id}", name="add_like")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticleAction(Request $request, Article $id)
    {
        $userId = new User();

        $userId->getId();

        $entityManager = $this->getDoctrine()->getManager();

        $like = new Like();

        $like->setCount("5");

        $like->setUserId($userId);

        $like->addArticleId($id);

        $entityManager->persist($like);

        $entityManager->persist($like);

        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @param Request $request
     * @param Article $id
     * @Route("/home/{id}", name="more")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moreAction(Request $request, Article $id)
    {

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->find($id);

        $comment = new Comments();
        
        $form = $this->createForm(CommentType::class, $comment);

        $firstName = $this->getUser()->getFirstName();

        $lastName = $this->getUser()->getLastName();

        $user = $firstName . ' ' . $lastName;

        $articleId = $id->getId();

        if ($request->getMethod() == Request::METHOD_POST) {
            
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $comment->setAuthor($firstName . ' ' . $lastName);
            $comment->setArticleId($articleId);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        $repository = $this->getDoctrine()->getRepository(Comments::class);

        $comments = $repository->findBy(['article_id' => $articleId]);


        return $this->render('article.html.twig', [
            'article' => $articles,
            'user' => $user,
            'form' => $form->createView(),
            'comments' => $comments,
        ]);
    }





    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(MessageService $messageGenerator)
    {
        $message = $messageGenerator->getMessage();

        return $this->render('admin/admin.html.twig', [
            'message' => $message
        ]);
    }

    
    /**
     * @Route("/blogger", name="blogger")
     */
    public function bloggerAction(MessageService $messageGenerator)
    {
        $message = $messageGenerator->getMessage();
      
        return $this->render('blogger/blogger.html.twig', [
            'message' => $message
        ]);
    }


}

