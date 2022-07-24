<?php

namespace App\Form;

use App\Entity\Sessions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CreateSessionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promotion')
            
            ->add('date_debut_promo', DateType::class, [
                'format'=>'d,M,y',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ]
            )
            ->add('date_fin_promo', DateType::class
            , [
                'format'=>'d,M,y',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
           
            ->add('ville')
            ->add('responsable');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sessions::class,
        ]);
    }
}
