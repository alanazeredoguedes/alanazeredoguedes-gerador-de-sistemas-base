<?php

namespace App\Application\Internit\DocumentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('titulo', TextType::class, [
            'label' => 'Titulo',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('subtitulo', TextType::class, [
            'label' => 'Subtitulo',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('descricao', TextareaType::class, [
            'label' => 'Descrição',
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
