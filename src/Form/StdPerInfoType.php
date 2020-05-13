<?php

namespace App\Form;

use App\Entity\StdPerInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class StdPerInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('age', DateType::class)
            ->add('cin')
            ->add('gendre', ChoiceType::class, [
    'choices'  => [
        'MALE' => 'MALE',
        'FEMALE' => 'FEMALE',
    ],
])
            ->add('city')
            ->add('phone')
            ->add('submit', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdPerInfo::class,
        ]);
    }
}
