<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Background;
use App\Entity\User;
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
use App\Service\BackgroundServices;
use App\Form\BackgroundType;

class AdminController extends Controller
{


    /**
     * Generate welcome message in admin page
     */


    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request, MessageService $messageGenerator, AvatarServices $fileUploader)
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

        return $this->render('admin/admin.html.twig', [
            'user' => $user,
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Change admin info
     */


    /**
     * @Route("/admin/update", name="update_admin_info")
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

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/updateAdmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Change blog background
     */


    /**
     * @param Request $request
     * @Route("/admin/update-background", name="update_background")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateBackgroundAction(Request $request, BackgroundServices $fileUploader)
    {
        $bg = new Background();
        $form = $this->createForm(BackgroundType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = $fileUploader->upload($file);
            $text = $form->get('text')->getData();
            $bg->setImage($fileName);
            $bg->setText($text);
            $em = $this->getDoctrine()->getManager();
            $em->persist($bg);
            $em->flush();

            return $this->redirect($this->generateUrl('admin'));
        }

        return $this->render('admin/updateBackground.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Get user list
     */
    
    
    /**
     * @Route("/admin/user/list", name="user-list")
     */
    public function editUsersAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $usersQuery = $repository->findBy([], ['id' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $usersQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/editUsers.html.twig', [
            'users' => $users,
        ]);
    }

    
    /**
     * Delete user
     */

    /**
     * @param Request $request
     * @param User $id
     * @Route("/admin/user/{id}/delete", name="delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserAction(Request $request, User $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $id;
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin');
    }


    /**
     * Add roles to user
     */


    /**
     * @param Request $request
     * @param User $id
     * @Route("/admin/user/{id}/{role}", name="add_roles")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addRoleAction(Request $request, User $id, $role)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $id;
        $em->persist($user);
        $user->setRoles([$role, 'ROLE_USER']);
        $em->flush();

        return $this->redirectToRoute('admin');
    }

    
    /**
     * Create article
     */


    /**
     * @param Request $request
     * @Route("/admin/add-article", methods={"GET", "POST"}, name="add-article")
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

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Edit article
     */


    /**
     * @Route("/admin/edit-article", name="edit-article")
     */
    public function editArticleAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $allArticles = $repository->findBy([], ['id' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $articles = $paginator->paginate(
            $allArticles,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('admin/editArticle.html.twig', [
            'articles' => $articles,
        ]);
    }

    
    /**
     * Delete article
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/admin/edit-article/delete/{id}", name="delete_article")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticleAction(Request $request, Article $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $id;
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('admin');
    }

    
    /**
     * Update article
     */


    /**
     * @param Request $request
     * @param Article $id
     * @Route("/admin/update-article/{id}", name="update_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateArticleAction(Request $request, Article $id, ImageServices $fileUploader)
    {
        $form = $this->createForm(ArticleType::class, $id);
        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost = $form->getData();
            $file = $blogPost->get('image')->getData();
            $fileName = $fileUploader->upload($file);
            $id->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('update_article', [
                'id' => $blogPost->getId(),
            ]);
        }

        return $this->render('admin/updateArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
