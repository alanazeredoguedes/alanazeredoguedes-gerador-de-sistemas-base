<?php

namespace App\Application\Internit\DocumentoBundle\Controller;

use App\Application\Internit\DocumentoBundle\Repository\CursoRepository;
use App\Application\Internit\DocumentoBundle\Repository\DocumentoRepository;
use App\Application\Project\ContentBundle\Attributes\Acl as ACL;

use App\Application\Project\ContentBundle\Controller\Base\BaseApiController;
use App\Application\Internit\DocumentoBundle\Entity\Documento;
use App\Application\Project\ContentBundle\Service\FilterDoctrine;
use App\Application\Project\ContentBundle\Service\MyAbstractNormalizer;
use App\Application\Project\ContentBundle\Service\MySerializar;
use App\Application\Project\ContentBundle\Service\SerializerObjects;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use OpenApi\Attributes as OA;
use ReflectionException;
use ReflectionObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/documentos', name: 'api_documentos_')]
#[OA\Tag(name: 'documentos', description: 'Acesso aos documentos')]
#[ACL\Api(enable: true, title: 'Documento', description: 'Permissões do modulo Documento')]
class DocumentoApiController extends BaseApiController
{

    public function getRepository(): string
    {
        return Documento::class;
    }

    /** @throws QueryException|ReflectionException */
    #[OA\Parameter( name: 'pagina', description: 'Número da Página', in: 'query', required: false, allowEmptyValue: true, example: 1)]
    #[OA\Parameter( name: 'paginaTamanho', description: 'Tamanho da Página', in: 'query', required: false, example: 10)]
    #[OA\Response(
        response: 200,
        description: 'Return list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Listar', description: 'Listar todos os Documento')]
    public function listAction(DocumentoRepository $repository, Request $request): Response
    {
        $this->validateAccess(actionName: "listAction");

        $filter = new FilterDoctrine(
            repository:  $repository,
            request: $request,
            attributesFilters: ['id', 'titulo', 'subtitulo', 'descricao', 'curso', 'imagem', 'galeria'],
        );

        $response = $this->serializerObjects->normalizer($filter->getResult()->data, [
            'id', 'titulo', 'subtitulo', 'descricao', 'imagem', 'galeria', 'curso',
        ]);

        return $this->json([
            '@id' => $request->getPathInfo(),
            'result' => $response,
            'paginator' => $filter->getResult()->paginator,
        ]);
    }

    #[OA\Response(
        response: 200,
        description: 'Return list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        description: 'Json Payload',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]

    #[Route('', name: 'create', methods: ['POST'])]
    #[ACL\Api(enable: true, title: 'Criar', description: 'Cria novo Documento')]
    public function createAction(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $this->validateAccess("createAction");

        $entityManager = $doctrine->getManager();

        $requestBody = json_decode($request->getContent());

        $obj = $this->cast('App\Application\Internit\DocumentoBundle\Entity\Documento', $requestBody);

        $errorMessage = $this->validateConstraintErros($validator, $obj);
        if($errorMessage)
            return $this->json($errorMessage, 400);


        $entityManager->persist($obj);
        $entityManager->flush();

        return $this->json([
            'status' => true,
            'message' => 'Documento Criado com sucesso',
            'id' => $obj->getId()
        ]);
    }

    public static function cast2($destination, \stdClass $source)
    {
        $sourceReflection = new \ReflectionObject($source);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $name = $sourceProperty->getName();
            $destination->{$name} = $source->$name;
        }
        return $destination;
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination,$value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }


    public function validateConstraintErros(ValidatorInterface $validator, $object): bool|array
    {
        $errors = $validator->validate($object);
        if (!count($errors))
            return false;

        $responseErrors = [
            'status' => false,
            'message' => 'Os seguintes erros foram encontrados!',
            'errors' => []
        ];

        foreach ($errors as $error){
            //dd($error);
            $responseErrors['errors'][] = [
                'propertyPath' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
                'invalidValue' => $error->getInvalidValue(),
            ];
        }

        return $responseErrors;
    }

    #[OA\Response(
        response: 200,
        description: 'Return data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Visualizar', description: 'Visualiza um Documento')]
    public function showAction(ManagerRegistry $doctrine, int $id): Response
    {
        $this->validateAccess("showAction");

        $data = $doctrine->getRepository($this->getRepository())->find($id);

        if (!$data)
            return $this->json([
                'status' => false,
                'message' => 'Documento não encontrado'
            ], 404);

        $response = $this->serializerObjects->normalizer($data, [
            'id', 'titulo', 'subtitulo', 'descricao', 'curso', 'imagem', 'galeria',
        ]);

        return $this->json($response);
    }


    #[OA\Response(
        response: 200,
        description: 'Return data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        description: 'Json Payload',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'titulo', type: 'string'),
                new OA\Property(property: 'subtitulo', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('/{id}', name: 'edit', methods: ['PUT','PATCH'])]
    #[ACL\Api(enable: true, title: 'Editar', description: 'Edita um Documento')]
    public function editAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess("editAction");
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();

        $parameters = [
            'titulo'     => [ 'type' => 'string', 'required' => true, 'nullable' => false ],
            'subtitulo'  => [ 'type' => 'string', 'required' => true, 'nullable' => false ],
            'descricao'  => [ 'type' => 'string', 'required' => false, 'nullable' => true ],
        ];

        $requestBody = json_decode($request->getContent());

        if($this->validateJsonRequestBody($requestBody, $parameters))
            return $this->validateJsonRequestBody($requestBody, $parameters);

        $data = $entityManager->getRepository($this->getRepository())->find($id);

        if (!$data)
            return $this->json([
                'status' => false,
                'message' => 'Documento não encontrado'
            ], 404);

        if(property_exists($requestBody, 'titulo'))
            $data->setTitulo($requestBody->titulo);

        if(property_exists($requestBody, 'subtitulo'))
            $data->setSubtitulo($requestBody->subtitulo);

        if(property_exists($requestBody, 'descricao'))
            $data->setDescricao($requestBody->descricao);

        $entityManager->persist($data);
        $entityManager->flush();

        $response = [
            'id' => $data->getId(),
            'name' => $data->getTitulo(),
            'subtitulo' => $data->getSubtitulo(),
            'descricao' => $data->getDescricao(),
        ];

        return $this->json($response);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[ACL\Api(enable: true, title: 'Deletar', description: 'Deleta um Documento')]
    public function deleteAction(ManagerRegistry $doctrine, int $id): Response
    {
        $this->validateAccess("deleteAction");

        $entityManager = $doctrine->getManager();

        $data = $entityManager->getRepository($this->getRepository())->find($id);

        /** Verifica se o documento existe */
        if (!$data)
            return $this->json([
                'status' => false,
                'message' => 'Error on Deleted { Documento } with id ' . $id,
            ], 404);

        /** Verifica se existem aplicações vinculadas ao diagrama */

        $entityManager->remove($data);
        $entityManager->flush();

        return $this->json([
            'status' => true,
            'message' => 'Documento removido com sucesso ',
        ]);
    }

}