<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\MessageService;
use App\Service\AvatarServices;
use App\Form\AvatarType;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ImageServices;

class BloggerController extends Controller
{
    
    /**
     * Generate welcome message in admin page
     */


    /**
     * @Route("/blogger", name="blogger")
     */
    public function bloggerAction(Request $request, MessageService $messageGenerator, AvatarServices $fileUploader)
    {
        $user = $this->getUser();
        $message = $messageGenerator->getMessage();
        $form = $this->createForm(AvatarType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('avatar')->getData();
            $fileName = $fileUploader->upload($file);
            $user->setAvatar($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('view_user_info'));
        }

        return $this->render('blogger/blogger.html.twig', [
            'user' => $user,
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Change blogger info
     */


    /**
     * @Route("/blogger/update", name="update_blogger_info")
     */
    public function updateBloggerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('blogger');
        }

        return $this->render('blogger/updateBlogger.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Create article
     */


    /**
     * @param Request $request
     * @Route("/blogger/add-article", methods={"GET", "POST"}, name="blogger_add_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, ImageServices $fileUploader)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setAuthor($this->getUser());
            $file = $form->get('image')->getData();
            $fileName = $fileUploader->upload($file);
            $article->setImage($fileName);
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blogger');
        }

        return $this->render('blogger/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Edit article
     */


    /**
     * @Route("/blogger/edit-article", name="blogger_edit_article")
     */
    public function bloggerEditArticleAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $userId = $this->getUser()->getId();
        $bloggerArticles = $repository->findBy(['author' => $userId], ['id' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $bloggerArticles,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('blogger/editArticle.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * Delete article
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/blogger/edit-article/{id}/delete", name="blogger_delete_article")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticleAction(Request $request, Article $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $id;
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('blogger');
    }


    /**
     * Update article
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/blogger/{id}/update-article/", name="blogger_update_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateArticleAction(Request $request, Article $id)
    {
        $form = $this->createForm(ArticleType::class, $id);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('blogger_update_article', [
                'id' => $blogPost->getId(),
            ]);
        }

        return $this->render('blogger/updateArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
