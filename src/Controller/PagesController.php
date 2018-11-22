<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\AdminMessageService;
use App\Service\HomePageService;
use Symfony\Component\Routing\Annotation\Route;
class PagesController extends AbstractController
{

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

