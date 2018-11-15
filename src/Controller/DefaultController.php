<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/registration", methods={"GET", "POST"}, name="app_register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($request->getMethod() == Request::METHOD_POST) {

            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('home');

        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {
        $faker = \Faker\Factory::create();

        $text = $faker->realText(500);

        $name = $faker->name;

        $img = $faker->imageUrl(640,440);

        $date = date('Y-m-d h:i');

        $title = $faker->realText(15);


        return $this->render('base.html.twig', [
            'article' => $text,
            'name' => $name,
            'date' => $date,
            'img' => $img,
            'title' => $title,
        ]);
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('admin.html.twig', [
            'message' => 'Welcome admin'
        ]);
    }
}
