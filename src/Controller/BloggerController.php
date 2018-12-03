<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\AddArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BloggerController extends AbstractController
{
    
    /**
     * @param Request $request
     * @Route("/blogger/add-article", methods={"GET", "POST"}, name="blogger_add_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bloggerAddArticleAction(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(AddArticleType::class, $article);

        $firstName = $this->getUser()->getFirstName();

        $lastName = $this->getUser()->getLastName();

        $userId = $this->getUser()->getId();
        
        if ($request->getMethod() == Request::METHOD_POST) {

            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();

            $em->persist($article);

            $article->setAuthor($firstName . ' ' . $lastName);

            $article->setAuthorId($userId);

            $em->flush();

            return $this->redirectToRoute('blogger');
        }        
               
        return $this->render('blogger/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/blogger/edit-article", name="blogger_edit_article")
     */
    public function bloggerEditArticleAction()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $userId = $this->getUser()->getId();

        $articles = $repository->findBy(['authorId' => $userId]);


        return $this->render('blogger/editArticle.html.twig', [
            'articles' => $articles,
        ]);
    }

}