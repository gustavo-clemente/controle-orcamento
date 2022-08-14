<?php

namespace App\Form;

use App\Request\ReceitaRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ReceitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descricao', TextType::class,[

                'required' => true,
                'constraints' => [

                    new NotBlank([

                        'message' => 'O campo não pode ser vázio'
                    ])
                ]
            ])
            ->add('valor', MoneyType::class, [

                'required' => true,
                'invalid_message' => 'Informe um valor decimal válido',
                'constraints' => [

                    new NotBlank([

                        'message' => 'O campo não pode ser vázio'
                    ])
                ]
            ])
            ->add('data', DateType::class,[

                'required' => true,
                'widget' => 'single_text',
                'invalid_message' => 'Informe uma data válida',
                'constraints' => [

                    new NotBlank([

                        'message' => 'O campo não pode ser vázio'
                    ])
                ]
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReceitaRequest::class
        ]);
    }
}
