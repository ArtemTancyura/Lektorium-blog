<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserLike;
use App\Form\AvatarType;
use App\Service\AvatarServices;

class UserController extends Controller
{

    /**
     * User page
     */


    /**
     * @Route("/user", name="user")
     */
    public function viewUsersAction(Request $request, AvatarServices $fileUploader)
    {
        $user = $this->getUser();

        if ($user->getId() == 100||$user->getId() == 1000||$user->getId() == 10000) {
            $message = "Congratulation! You our ".$user->getId().
                " site user! Surprise waiting for you! Write to admin for details";
        } else {
            $message = " Hello, ".$user->getFirstName();
        }
        
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

        return $this->render('user/user.html.twig', [
            'user' => $user,
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }
    
    
    /**
     * Change user info
     */


    /**
     * @Route("/user/update", name="update_user_info")
     */
    public function updateUsersAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/updateUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
