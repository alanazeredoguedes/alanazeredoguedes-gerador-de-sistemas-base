<?php
namespace App\Application\Internit\DocumentoBundle\Admin;

use App\Application\Internit\CursoBundle\Entity\Curso;
use App\Application\Project\ContentBundle\Admin\Base\BaseAdmin;
use App\Application\Internit\DocumentoBundle\Entity\Documento;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class DocumentoAdmin extends BaseAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof Documento ? $object->getId() . " - " .  $object->getTitulo() : '';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);

        //$collection->add('login');
    }

    protected function configureFormFields(FormMapper $form): void
    {

        $form->add('titulo', TextType::class);
        $form->add('subtitulo', TextType::class);
        $form->add('descricao', TextareaType::class);
        $form->add('imagem', ModelListType::class,[
            'label' => 'Imagem: ',
        ]);
        $form->add('galeria', ModelListType::class,[
            'label' => 'Galeria: ',
        ]);
        $form->add('curso', ModelType::class,[
            'class' => Curso::class,
            'property' => 'nome',
            'label' => 'Curso',
            'required' => true,
            'expanded' => false,
            'btn_add' => false,
            'multiple' => false,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('titulo');
        $datagrid->add('subtitulo');
        $datagrid->add('descricao');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('imagem', null, [
            'template' => '@SonataMedia/MediaAdmin/list_image.html.twig'
        ]);
        $list->addIdentifier('titulo', null, ['route' => ['name' => 'edit']]);
        $list->addIdentifier('subtitulo');
        $list->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ]
        ]);    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('titulo');
        $show->add('subtitulo');
        $show->add('descricao');
    }
}