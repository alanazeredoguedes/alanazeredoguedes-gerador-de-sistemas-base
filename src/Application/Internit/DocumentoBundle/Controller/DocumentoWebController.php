<?php

namespace App\Application\Internit\DocumentoBundle\Controller;

use App\Application\Project\ContentBundle\Attributes\Acl as ACL;
use App\Application\Project\ContentBundle\Controller\Base\BaseWebController;
use App\Application\Internit\DocumentoBundle\Entity\Documento;
use App\Application\Internit\DocumentoBundle\Form\EmpreendimentoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/web/documentos', name: 'web_documento_', methods: ['GET'])]
#[ACL\Web(enable: true, title: 'Documento', description: 'Permissões do modulo Documento')]
class DocumentoWebController extends BaseWebController
{
    public function getBaseRouter(): string
    {
        return 'web_documento_';
    }

    public function getRepository(): string
    {
        return Documento::class;
    }

    public function getBaseTemplate(): string
    {
        return "@ApplicationInternitDocumento/documento/";
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Listar', description: 'Lista todos os Documento')]
    public function listAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'listAction');

        $objects = $doctrine->getRepository($this->getRepository())->findAll();

        return $this->render($this->getBaseTemplate() . 'list.html.twig', [
            'title' => 'Documento',
            'objects' => $objects,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Criar', description: 'Cria um Documento')]
    public function createAction(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->validateAccess(actionName: 'createAction');

        $entityManager = $doctrine->getManager();

        $documento = new Documento();
        $form = $this->createForm(EmpreendimentoType::class, $documento);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $documento = $form->getData();

            $entityManager->persist($documento);
            $entityManager->flush();

            return $this->redirectToRoute($this->getBaseRouter() . 'list');
        }

        return $this->render($this->getBaseTemplate() . 'create.html.twig',[
            'title' => 'Documento',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Visualizar', description: 'Visualiza um Documento')]
    public function showAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'showAction');

        $object = $doctrine->getRepository($this->getRepository())->find($id);

        return $this->render($this->getBaseTemplate() . 'show.html.twig',[
            'title' => 'Documento',
            'object' => $object,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ACL\Web(enable: true, title: 'Editar', description: 'Edita um Documento')]
    public function editAction(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $this->validateAccess(actionName: 'editAction');

        $entityManager = $doctrine->getManager();

        $data = $entityManager->getRepository($this->getRepository())->find($id);

        if (!$data)
            return $this->redirectToRoute($this->getBaseRouter() . 'list');

        $form = $this->createForm(EmpreendimentoType::class, $data);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute($this->getBaseRouter() . 'list');
        }


        return $this->render($this->getBaseTemplate() . 'edit.html.twig',[
            'title' => 'Documento',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    #[ACL\Web(enable: true, title: 'Deletar', description: 'Deleta um Documento')]
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