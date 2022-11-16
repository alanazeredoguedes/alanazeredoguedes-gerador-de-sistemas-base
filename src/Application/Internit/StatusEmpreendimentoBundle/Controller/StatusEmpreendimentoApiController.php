<?php

namespace App\Application\Internit\StatusEmpreendimentoBundle\Controller;

use App\Application\Internit\StatusEmpreendimentoBundle\Repository\StatusEmpreendimentoRepository;
use App\Application\Internit\StatusEmpreendimentoBundle\Entity\StatusEmpreendimento;

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
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/statusEmpreendimento', name: 'api_statusEmpreendimento_')]
#[OA\Tag(name: 'StatusEmpreendimento', description: 'Acesso ao statusEmpreendimento')]
#[ACL\Api(enable: true, title: 'StatusEmpreendimento', description: 'Permissões do modulo StatusEmpreendimento')]
class StatusEmpreendimentoApiController extends BaseApiController
{

    public function getClass(): string
    {
        return StatusEmpreendimento::class;
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
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Listar', description: 'Listar StatusEmpreendimento')]
    public function listAction(ManagerRegistry $doctrine, Request $request): Response
    {
        //$this->validateAccess(actionName: "listAction");

        $filter = new FilterDoctrine(
            repository:  $this->getRepository($doctrine),
            request: $request,
            attributesFilters: ['id','status','descricao',],
        );

        $response = $this->serializerObjects->normalizer($filter->getResult()->data, [
            'id','status','descricao',
        ]);

        return $this->json([
            '@id' => $request->getPathInfo(),
            'result' => $response,
            'paginator' => $filter->getResult()->paginator,
        ]);

    }

    /** @throws ReflectionException */
    #[OA\Response(
        response: 200,
        description: 'Return list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[ACL\Api(enable: true, title: 'Visualizar', description: 'Visualizar StatusEmpreendimento')]
    public function showAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        //$this->validateAccess("showAction");

        $data = $this->getRepository($doctrine)->find($id);
        if (!$data)
            return $this->json(['status' => false, 'message' => 'StatusEmpreendimento não encontrado!'], 404);

        $response = $this->serializerObjects->normalizer($data, [
            'id','status','descricao',
        ]);

        return $this->json([
            '@id' => $request->getPathInfo(),
            'result' => $response,
        ]);
    }


    #[OA\Response(
        response: 200,
        description: 'Return list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        description: 'Json Payload',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'descricao', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[Route('', name: 'create', methods: ['POST'])]
    #[ACL\Api(enable: true, title: 'Criar', description: 'Criar StatusEmpreendimento')]
    public function createAction(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
       //$this->validateAccess("createAction");

        /** Pega o corpo da requisição e faz valida se não é nulo */
        $requestBody = json_decode($request->getContent());
        if(!$requestBody)
            return $this->json(['status' => false, 'message' => 'Corpo da requisição invalido!'], 400);

        /** Cria uma instância de uma classe com as informações do corpo da requisição */
        $obj = $this->cast(StatusEmpreendimento::class, $requestBody);

        /** Faz validação da instância da classe! */
        $errorMessage = $this->validateConstraintErros($validator, $obj);
        if($errorMessage)
            return $this->json($errorMessage, 400);

        /** Faz a persistência dos dados! */
        $em = $doctrine->getManager();
        $em->persist($obj);
        $em->flush();


        return $this->json([
            'status' => true,
            'message' => 'Status Empreendimento criado com sucesso',
            'id' => $obj->getId()
        ]);

    }


    #[Route('/{id}', name: 'edit', methods: ['PUT','PATCH'])]
    #[ACL\Api(enable: true, title: 'Editar', description: 'Editar StatusEmpreendimento')]
    public function editAction(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        //$this->validateAccess("editAction");

        /** Pega o corpo da requisição e faz valida se não é nulo */
        $requestBody = json_decode($request->getContent());
        if(!$requestBody)
            return $this->json(['status' => false, 'message' => 'Corpo da requisição invalido!'], 400);

        /** Cria uma instância de uma classe com as informações do corpo da requisição */
        $obj = $this->cast(StatusEmpreendimento::class, $requestBody);








        /** Faz validação da instância da classe! */
        $errorMessage = $this->validateConstraintErros($validator, $obj);
        if($errorMessage)
            return $this->json($errorMessage, 400);

        /** Faz a persistência dos dados! */
        /*$em = $doctrine->getManager();
        $em->persist($obj);
        $em->flush();*/


        return $this->json([
            'status' => true,
            'message' => 'Status Empreendimento Atualizado com sucesso',
            'id' => $obj->getId()
        ]);

    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[ACL\Api(enable: true, title: 'Deletar', description: 'Deletar StatusEmpreendimento')]
    public function deleteAction(ManagerRegistry $doctrine, int $id): Response
    {
        //$this->validateAccess("deleteAction");

        $data = $this->getRepository($doctrine)->find($id);
        if (!$data)
            return $this->json(['status' => false, 'message' => 'StatusEmpreendimento não encontrado!'], 404);

        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();

        return $this->json(['status' => true, 'message' => 'StatusEmpreendimento removido com sucesso!']);
    }

}