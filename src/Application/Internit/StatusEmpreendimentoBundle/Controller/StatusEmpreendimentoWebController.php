<?php

namespace App\Application\Internit\StatusEmpreendimentoBundle\Controller;

use App\Application\Internit\StatusEmpreendimentoBundle\Entity\StatusEmpreendimento;
use App\Application\Internit\StatusEmpreendimentoBundle\Form\StatusEmpreendimentoType;

use App\Application\Project\ContentBundle\Controller\Base\BaseWebController;
use App\Application\Project\ContentBundle\Attributes\Acl as ACL;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/web/statusEmpreendimento', name: 'web_statusEmpreendimento_', methods: ['GET'])]
#[ACL\Web(enable: true, title: 'StatusEmpreendimento', description: 'Permissões do modulo StatusEmpreendimento')]
class StatusEmpreendimentoWebController extends BaseWebController
{
    public function getBaseRouter(): string
    {
        return 'web_statusEmpreendimento_';
    }

    public function getRepository(): string
    {
        return StatusEmpreendimento::class;
    }

    public function getBaseTemplate(): string
    {
        return "@ApplicationInternitStatusEmpreendimento/statusempreendimento/";
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Listar', description: 'Lista StatusEmpreendimento')]
    public function listAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'listAction');

        $objects = $doctrine->getRepository($this->getRepository())->findAll();

        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Listar Status Empreendimento',
            'objects' => $objects,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Criar', description: 'Cria StatusEmpreendimento')]
    public function createAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'createAction');

        $entityManager = $doctrine->getManager();

        $empreendimento = new StatusEmpreendimento();
        $form = $this->createForm(StatusEmpreendimentoType::class, $empreendimento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $empreendimento = $form->getData();

            $entityManager->persist($empreendimento);
            $entityManager->flush();

            return $this->redirectToRoute($this->getBaseRouter() . 'list');
        }

        return $this->render($this->getBaseTemplate() . 'create.html.twig',[
            'title' => 'Criar Status Empreendimento',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Visualizar', description: 'Visualiza StatusEmpreendimento')]
    public function showAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'showAction');

        $empreendimento = $doctrine->getRepository($this->getRepository())->find($id);
        $form = $this->createForm(StatusEmpreendimentoType::class, $empreendimento);

        return $this->render($this->getBaseTemplate() . 'show.html.twig',[
            'title' => 'Visualizar Status Empreendimento',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Editar', description: 'Edita StatusEmpreendimento')]
    public function editAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'editAction');
        $entityManager = $doctrine->getManager();

        $empreendimento = $doctrine->getRepository($this->getRepository())->find($id);
        $form = $this->createForm(StatusEmpreendimentoType::class, $empreendimento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $empreendimento = $form->getData();

            $entityManager->persist($empreendimento);
            $entityManager->flush();

            return $this->redirectToRoute($this->getBaseRouter() . 'list');
        }

        return $this->render($this->getBaseTemplate() . 'edit.html.twig',[
            'title' => 'Editar Status Empreendimento',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Deletar', description: 'Deleta StatusEmpreendimento')]
    public function deleteAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'deleteAction');

        $entityManager = $doctrine->getManager();

        $data = $entityManager->getRepository($this->getRepository())->find($id);

        if (!$data)
            return $this->redirectToRoute($this->getBaseRouter() . 'list');

        $entityManager->remove($data);
        $entityManager->flush();

        return $this->redirectToRoute($this->getBaseRouter() . 'list');
    }

}