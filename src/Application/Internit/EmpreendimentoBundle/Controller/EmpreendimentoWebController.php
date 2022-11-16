<?php

namespace App\Application\Internit\EmpreendimentoBundle\Controller;

use App\Application\Internit\EmpreendimentoBundle\Entity\Empreendimento;
use App\Application\Internit\EmpreendimentoBundle\Form\EmpreendimentoType;

use App\Application\Project\ContentBundle\Controller\Base\BaseWebController;
use App\Application\Project\ContentBundle\Attributes\Acl as ACL;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/web/empreendimento', name: 'web_empreendimento_', methods: ['GET'])]
#[ACL\Web(enable: true, title: 'Empreendimento', description: 'Permissões do modulo Empreendimento')]
class EmpreendimentoWebController extends BaseWebController
{
    public function getBaseRouter(): string
    {
        return 'web_empreendimento_';
    }

    public function getRepository(): string
    {
        return Empreendimento::class;
    }

    public function getBaseTemplate(): string
    {
        return "@ApplicationInternitEmpreendimento/empreendimento/";
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Listar', description: 'Lista Empreendimento')]
    public function listAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'listAction');

        $objects = $doctrine->getRepository($this->getRepository())->findAll();

        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Listar Empreendimento',
            'objects' => $objects,
        ]);

    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Criar', description: 'Cria Empreendimento')]
    public function createAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'createAction');

        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Criar Empreendimento',
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Visualizar', description: 'Visualiza Empreendimento')]
    public function showAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'showAction');


        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Visualizar Empreendimento',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Editar', description: 'Edita Empreendimento')]
    public function editAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'editAction');

        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Editar Empreendimento',
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Deletar', description: 'Deleta Empreendimento')]
    public function deleteAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'deleteAction');

    }

}