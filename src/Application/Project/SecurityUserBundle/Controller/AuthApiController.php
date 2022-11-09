<?php

namespace App\Application\Project\SecurityUserBundle\Controller;

use App\Application\Project\ContentBundle\Controller\Base\BaseApiController;
use App\Application\Project\SecurityBundle\Entity\ApiUser;
use App\Application\Project\SecurityUserBundle\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use ReflectionClass;
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
    private ?JWTTokenManagerInterface $JWTManager = null;

    public function __construct(JWTTokenManagerInterface $jwt = null) {
        $this->JWTManager = $jwt;
    }

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

        $user = $this->validateUserProvider($doctrine, $passwordHasher, $requestBody->email, $requestBody->password);

        if(!$user)
            return $this->createResponseStatus(message: 'Invalid access credentials');

        //dd($user);

        $token = $this->JWTManager->create($user);

        return $this->json(['token' => $token]);
    }

    /**
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
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
            'id', 'nome', 'email',
            'roles',
            'groups' => ['id', 'name', 'description']
        ] ]);
        $data['provider'] = (new ReflectionClass($user))->getShortName();

        return $this->json($data);
    }








    protected function validateUserProvider(ManagerRegistry $doctrine, $passwordHasher,  $email, $password)
    {
        $user = $this->getUserByProvider(doctrine: $doctrine, provider: 'API', email: $email);

        if($user && $passwordHasher->isPasswordValid($user, $password))
            return $user;

        return false;
    }


    /**
     * @param ManagerRegistry $doctrine
     * @param $provider - ADMIN - API - WEB
     * @param $email - Provider Email
     * @return mixed
     */
    protected function getUserByProvider(ManagerRegistry $doctrine, $provider, $email): mixed
    {
        $providers = match (strtolower($provider)) {
            'admin' => $this->getAdminProvider(),
            'api' => $this->getApiProvider(),
            'web' => $this->getWebProvider(),
            default => false,
        };

        if(!$providers)
            return false;

        foreach ($providers as $provider) {
            return $doctrine->getManager()->getRepository($provider)->findOneBy(['email' => $email]);
        }

        return false;
    }

    protected function getAdminProvider(): array
    {
        return [
            'App\Application\Project\SecurityProviderAdminBundle\Entity\UserAdmin'
        ];
    }

    protected function getApiProvider(): array
    {
        return [
            'App\Application\Project\SecurityUserBundle\Entity\User'
        ];
    }

    protected function getWebProvider(): array
    {
        return [
            'App\Application\Project\SecurityProviderWebBundle\Entity\UserWeb'
        ];
    }


    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/loginweb', name: 'loginweb', methods: ['GET', "POST"])]
    public function loginWebAction(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@ApplicationProjectContent/auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    #[Route('/logoutweb', name: 'logoutweb', methods: ['GET'])]
    public function logoutAction(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /** @return Response */
    public function accessDeniedAction(): Response
    {
        return $this->render('@ApplicationProjectContent/error/error_403.html.twig');
    }



























}