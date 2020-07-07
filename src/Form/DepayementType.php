<?php

namespace App\Form;

use App\Entity\Month;
use App\Entity\Prof;
use App\Entity\Depayement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DepayementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prof', EntityType::class, [
           'class'=>Prof::class,
           'choice_label' => 'name'
           
            ] )
            ->add('price')
            ->add('month', EntityType::class, [
           'class'=>Month::class,
           'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depayement::class,
        ]);
    }
}
