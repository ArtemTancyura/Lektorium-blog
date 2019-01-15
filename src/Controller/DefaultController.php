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
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MessageService;


class DefaultController extends AbstractController
{

    /**
     * Login logic
     */


    /**
     * @Route("/login", name="app_login")
     * @Route("/",)
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, [
            'email' => $lastUsername,
        ]);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            return $this->redirectToRoute('home');
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }


    /**
     * Registration logic
     */


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
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }
}
