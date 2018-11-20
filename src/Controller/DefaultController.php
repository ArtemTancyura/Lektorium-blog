<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Service\AdminMessageService;
use App\Service\HomePageService;

class AuthController extends AbstractController
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

        $form = $this->createForm(LoginType::class, [
            'email' => $lastUsername,
        ]);
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/registration", methods={"GET", "POST"}, name="app_register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($request->getMethod() == Request::METHOD_POST) {

            $form->handleRequest($request);

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

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
    public function homeAction(HomePageService $pageGenerator)
    {

        $content = $pageGenerator->getPage();

        return $this->render('base.html.twig', [
            'article' => $content['text'],
            'name' => $content['name'],
            'date' => $content['date'],
            'img' => $content['img'],
            'title' => $content['title'],
        ]);
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(AdminMessageService $messageGenerator)
    {
        $message = $messageGenerator->getAdminMessage();

        return $this->render('admin.html.twig', [
            'message' => $message
        ]);
    }
}
