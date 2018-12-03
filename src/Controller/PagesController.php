<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MessageService;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();
        
        $firstName = $this->getUser()->getFirstName();

        $lastName = $this->getUser()->getLastName();
        
        $user = $firstName . ' ' . $lastName;

        return $this->render('base.html.twig', [
            'articles' => $articles,
            'user' => $user,
        ]);
    }
    
    /**
     * @Route("/home/{$id}", name="more")
     */
    public function moreAction($id)
    {

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->find($id);

        $firstName = $this->getUser()->getFirstName();

        $lastName = $this->getUser()->getLastName();

        $user = $firstName . ' ' . $lastName;

        return $this->render('article.html.twig', [
            'articles' => $articles,
            'user' => $user,
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

