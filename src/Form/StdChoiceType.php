<?php

namespace App\Form;

use App\Entity\StdChoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StdChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bactype',ChoiceType::class , [
    'choices'  => [
        'SVT' => 'SVT',
        'PHYS' => 'PHYS',
        'MATH' => 'MATH',
    ],
])
            ->add('submit', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdChoice::class,
        ]);
    }
}
