<?php

namespace App\Controller\API;


use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\JsonHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UserController extends Controller
{
    /**
     * @Route("api/get-user/{id}", name="get_user")
     */
    public function index(User $user)
    {
        return $this->json($user);
    }

    /**
     * @Route("/api/articles/new", name="api_articles_add")
     */
    public function addArticleAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, RouterInterface $router)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /** @var Article $article */
        $article = $serializer->deserialize($request->getContent(),Article::class,'json');
        $errors = $validator->validate($article);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->json($article);
    }
}