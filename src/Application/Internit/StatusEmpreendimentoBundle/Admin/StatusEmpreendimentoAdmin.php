<?php
namespace App\Application\Internit\StatusEmpreendimentoBundle\Admin;

use App\Application\Internit\StatusEmpreendimentoBundle\Entity\StatusEmpreendimento;

use App\Application\Project\ContentBundle\Admin\Base\BaseAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/** Components Form */
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StatusEmpreendimentoAdmin extends BaseAdmin
{

    public function toString(object $object): string
    {
        return $object instanceof StatusEmpreendimento ? $object->getId()
        .' - '.$object->getStatus()
        : '';
    }



    protected function configureFormFields(FormMapper $form): void
    {
        $form->tab('Geral');
            $form->with('Informações Gerais');


                $form->add('status',  TextType::class, [
                    'label' => 'Status',
                    'required' =>  true ,
                ]);

                $form->add('descricao',  TextType::class, [
                    'label' => 'Descricao',
                    'required' =>  false ,
                ]);

            $form->end();
        $form->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id', null, [
            'label' => 'Id',
        ]);

        $datagrid->add('status', null, [
            'label' => 'Status',
        ]);

        $datagrid->add('descricao', null, [
            'label' => 'Descricao',
        ]);

    }

    protected function configureListFields(ListMapper $list): void
    {

        $list->addIdentifier('id', null, [
            'label' => 'Id',
        ]);


        $list->addIdentifier('status', null, [
            'label' => 'Status',
        ]);


        $list->addIdentifier('descricao', null, [
            'label' => 'Descricao',
        ]);


        $list->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
            'actions' => [
                'show'   => [],
                'edit'   => [],
                'delete' => [],
            ]
        ]);

    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->tab('Geral');
            $show->with('Informações Gerais', [
                'class'       => 'col-md-12',
                'box_class'   => 'box box-solid box-primary',
                'description' => 'Informações Gerais',
            ]);

                $show->add('id', null, [
                    'label' => 'Id',
                ]);

                $show->add('status', null, [
                    'label' => 'Status',
                ]);

                $show->add('descricao', null, [
                    'label' => 'Descricao',
                ]);


            $show->end();
        $show->end();
    }


}