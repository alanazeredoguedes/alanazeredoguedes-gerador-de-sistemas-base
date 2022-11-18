<?php

namespace App\Application\Internit\EmpreendimentoBundle\Controller;

use App\Application\Internit\EmpreendimentoBundle\Repository\EmpreendimentoRepository;
use App\Application\Internit\EmpreendimentoBundle\Entity\Empreendimento;

use App\Application\Project\ContentBundle\Controller\Base\BaseApiController;
use App\Application\Project\ContentBundle\Service\FilterDoctrine;
use App\Application\Project\ContentBundle\Attributes\Acl as ACL;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\QueryException;
use ReflectionException;

#[Route('/api/empreendimento', name: 'api_empreendimento_')]
#[OA\Tag(name: 'empreendimento', description: 'Acesso ao empreendimento')]
#[ACL\Api(enable: true, title: 'Empreendimento', description: 'Permissões do modulo Empreendimento')]
class EmpreendimentoApiController extends BaseApiController
{

    public function getClass(): string
    {
        return Empreendimento::class;
    }

    public function getRepository(ManagerRegistry $doctrine): ObjectRepository
    {
        return $doctrine->getManager()->getRepository($this->getClass());
    }

    /**@throws QueryException|ReflectionException */
    #[OA\Parameter( name: 'pagina', description: 'Número da Página', in: 'query', required: false, allowEmptyValue: true, example: 1)]
    #[OA\Parameter( name: 'paginaTamanho', description: 'Tamanho da Página', in: 'query', required: false, example: 10)]
    #[OA\Response(
            response: 200,
            description: 'Return list',
            content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'nome', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
                new OA\Property(property: 'visivel', type: 'boolean'),
                new OA\Property(property: 'status', type: 'integer'),
                new OA\Property(property: 'blocos', type: 'integer'),
                new OA\Property(property: 'imagem', type: 'object'),
                new OA\Property(property: 'galeria', type: 'object'),
            ],
            type: 'object'
        )
    )]
    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Listar', description: 'Listar Empreendimento')]
    public function listAction(ManagerRegistry $doctrine, Request $request): Response
    {
        //$this->validateAccess(actionName: "listAction");

        $filter = new FilterDoctrine(
            repository:  $this->getRepository($doctrine),
            request: $request,
            attributesFilters: ['id','nome','descricao','visivel','status','blocos', 'imagem', 'galeria',],
        );

        $response = $this->serializerObjects->normalizer($filter->getResult()->data, [
            'id','nome','descricao','visivel','status','blocos', 'imagem', 'galeria',
        ]);

        return $this->json([
            '@id' => $request->getPathInfo(),
            'result' => $response,
            'paginator' => $filter->getResult()->paginator,
        ]);

    }


    #[Route('', name: 'create', methods: ['POST'])]
    #[ACL\Api(enable: true, title: 'Criar', description: 'Criar Empreendimento')]
    public function createAction(): Response
    {
        $this->validateAccess("createAction");

    }

    /** @throws ReflectionException */
    #[OA\Response(
        response: 200,
        description: 'Return list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'nome', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
                new OA\Property(property: 'visivel', type: 'boolean'),
                new OA\Property(property: 'status', type: 'integer'),
                new OA\Property(property: 'blocos', type: 'integer'),
                new OA\Property(property: 'imagem', type: 'object'),
                new OA\Property(property: 'galeria', type: 'object'),
            ],
            type: 'object'
        )
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Visualizar', description: 'Visualizar Empreendimento')]
    public function showAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        //$this->validateAccess("showAction");

        $data = $this->getRepository($doctrine)->find($id);
        if (!$data)
            return $this->json(['status' => false, 'message' => 'Empreendimento não encontrado!'], 404);

        $response = $this->serializerObjects->normalizer($data, [
            'id','nome','descricao','visivel','status','blocos', 'imagem', 'galeria',
        ]);

        return $this->json([
            '@id' => $request->getPathInfo(),
        '   result' => $response,
        ]);
    }


    #[Route('/{id}', name: 'edit', methods: ['PUT','PATCH'])]
    #[ACL\Api(enable: true, title: 'Editar', description: 'Editar Empreendimento')]
    public function editAction(): Response
    {
        $this->validateAccess("editAction");


    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[ACL\Api(enable: true, title: 'Deletar', description: 'Deletar Empreendimento')]
    public function deleteAction(ManagerRegistry $doctrine, int $id): Response
    {
        //$this->validateAccess("deleteAction");

        $data = $this->getRepository($doctrine)->find($id);
        if (!$data)
            return $this->json(['status' => false, 'message' => 'Empreendimento não encontrado!'], 404);

        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();

        return $this->json(['status' => true, 'message' => 'Empreendimento removido com sucesso!']);
    }

}