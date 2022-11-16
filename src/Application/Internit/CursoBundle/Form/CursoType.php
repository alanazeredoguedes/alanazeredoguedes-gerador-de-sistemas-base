<?php

namespace App\Application\Internit\CursoBundle\Form;

use App\Application\Internit\CursoBundle\Entity\Curso;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CursoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nome', TextType::class);
        $builder->add('imagem', ModelListType::class,[
            'label' => 'Imagem: ',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Curso::class,
        ]);
    }

    public function getName()
    {
        return 'curso_type';
    }

    public function getDefaultOptions(array $options){
        return array('data_class' => 'App\Application\Internit\CursoBundle\Entity\Curso');
    }

}