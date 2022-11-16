<?php

namespace App\Application\Internit\StatusEmpreendimentoBundle\Form;

use App\Application\Internit\StatusEmpreendimentoBundle\Entity\StatusEmpreendimento;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusEmpreendimentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('status', TextType::class, [
            'label' => 'Status',
            'required' => false,
            'attr' => ['class' => 'form-control mb-2'],
        ]);

        $builder->add('descricao', TextType::class, [
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
