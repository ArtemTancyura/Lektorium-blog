<?php

namespace App\Controller\API;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\JsonHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;
class UserController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @Route("api/get-user/{id}", name="get_user")
     */
    public function index(User $user)
    {
        return $this->json($user);
    }

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/api/user/new", name="api_user_add")
     */
    public function addUserAction(Request $request, ValidatorInterface $validator)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        /** @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, JsonEncoder::FORMAT);
        $user->setApiToken($uuid4 = Uuid::uuid4());
        $errors = $validator->validate($user);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request1');
        }
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->json($user);
    }
}
