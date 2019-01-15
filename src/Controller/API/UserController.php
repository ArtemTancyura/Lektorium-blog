<?php

namespace App\Controller\API;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HappyController extends Controller
{
    /**
     * @Route("/happy", name="happy")
     */
    public function index()
    {
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}