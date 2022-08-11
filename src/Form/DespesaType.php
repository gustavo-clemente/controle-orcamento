<?php

namespace App\Form;

use App\Entity\Despesa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DespesaType extends AbstractType
{

    public function __construct()
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descricao')
            ->add('valor')
            ->add('data', DateType::class,[

                'widget' => 'single_text'
            ])
            ->add('categoria', TextType::class,[

                'empty_data' => "Outras",
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
            'data_class' => Despesa::class,
        ]);
    }
}
