<?php
namespace App\Application\Internit\CursoBundle\Admin;

use App\Application\Internit\CursoBundle\Entity\Curso;
use App\Application\Project\ContentBundle\Admin\Base\BaseAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CursoAdmin extends BaseAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof Curso ? $object->getId() . " - " .  $object->getNome() : '';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);

        //$collection->add('login');
    }

    protected function configureFormFields(FormMapper $form): void
    {

        $form->add('nome', TextType::class);
        $form->add('imagem', ModelListType::class,[
            'label' => 'Imagem: ',
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('nome');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('imagem', null, [
            'template' => '@SonataMedia/MediaAdmin/list_image.html.twig'
        ]);
        $list->addIdentifier('nome');
        $list->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ]
        ]);    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('nome');
    }
}