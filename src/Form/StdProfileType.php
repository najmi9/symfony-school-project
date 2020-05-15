<?php

namespace App\Form;

use App\Entity\StdProfile;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StdProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state',  ChoiceType::class,[
                'choices'=>[
                     'ACCEPTED' => 'ACCEPTED',
                     'REJECTED' => 'REJECTED',
                     'NOT VERIFIED'=>'NOT VERIFIED'
                ]
            ])
            ->add('note')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdProfile::class,
        ]);
    }
}
