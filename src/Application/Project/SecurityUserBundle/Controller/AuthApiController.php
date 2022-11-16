<?php

namespace App\Application\Project\SecurityUserBundle\Controller;

use App\Application\Project\ContentBundle\Controller\Base\BaseApiController;
use App\Application\Project\SecurityBundle\Entity\ApiUser;
use App\Application\Project\SecurityUserBundle\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'auth')]
#[Route('/api', name: 'api_auth_')]
class AuthApiController extends BaseApiController
{

    #[OA\Response(
        response: 200,
        description: 'Return Token JWT',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', description: 'Token JWT', type: 'string', nullable: false),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        description: 'Json Payload',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email',  type: 'string', nullable: false),
                new OA\Property(property: 'password', type: 'string', nullable: false)
            ],
            type: 'object'
        )
    )]
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function loginAction(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $parameters = [
            'email'     => [ 'type' => 'string', 'required' => true, 'nullable' => false ],
            'password'  => [ 'type' => 'string', 'required' => true, 'nullable' => false ],
        ];

        $requestBody = json_decode($request->getContent());

        if($this->validateJsonRequestBody($requestBody, $parameters))
            return $this->validateJsonRequestBody($requestBody, $parameters);


        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $requestBody->email]);
        if(!$user || !$passwordHasher->isPasswordValid($user, $requestBody->password))
            return $this->createResponseStatus(message: 'Invalid access credentials');


        $token = $this->JWTTokenManager->create($user);

        return $this->json(['token' => $token]);
    }


    /** @throws ExceptionInterface|ReflectionException */
    #[OA\Response(
        response: 200,
        description: 'Return authenticated user',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'groups', type: 'object'),
            ],
            type: 'object'
        )
    )]
    #[Route('/user_authenticated', name: 'user_authenticated', methods: ['GET'])]
    public function userAuthenticatedAction(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $this->validateAccess("IS_AUTHENTICATED_FULLY");

        $user = $this->getUser();
        //dd($user);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($user, null, [AbstractNormalizer::ATTRIBUTES => [
            'id', 'name', 'email', 'roles',
            'groups' => ['id', 'name', 'description']
        ] ]);

        $data['provider'] = (new ReflectionClass($user))->getShortName();

        return $this->json($data);
    }



}