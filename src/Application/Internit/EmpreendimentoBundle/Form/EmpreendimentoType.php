<?php

namespace App\Application\Internit\EmpreendimentoBundle\Form;

use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpreendimentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('nome', TextType::class, [
            'label' => 'Nome',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('descricao', TextType::class, [
            'label' => 'Descrição',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('visivel', CheckboxType::class, [
            'label' => 'Visivel',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('status', ModelType::class, [
            'label' => 'Status',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('enviar', SubmitType::class, [
            'attr' => ['type' => 'submit', 'class' => 'save btn btn-primary' ],
        ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
