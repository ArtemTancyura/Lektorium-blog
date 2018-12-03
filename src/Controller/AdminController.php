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

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/edit-user", name="edit-user")
     */
    public function editUsersAction()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $users = $repository->findAll();

        return $this->render('admin/editUsers.html.twig', [
            'users' => $users,
        ]);
    }



    /**
     * @param Request $request
     * @param User $id
     * @Route("/admin/edit-user/delete/{$id}", name="delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserAction(Request $request, User $id)
    {

            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(User::class)->find($id);
            $em->remove($product);
            $em->flush();

            return $this->redirectToRoute('admin');
    }



    /**
     * @param Request $request
     * @Route("/admin/add-article", methods={"GET", "POST"}, name="add-article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
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

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/edit-article", name="edit-article")
     */
    public function editArticleAction()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();


        return $this->render('admin/editArticle.html.twig', [
            'articles' => $articles,
        ]);
    }


}