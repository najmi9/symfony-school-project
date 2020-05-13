<?php

namespace App\Form;

use App\Entity\Prof;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Classe;
use App\Entity\Student;


class ProfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('salaire')
            ->add('password')
            ->add('matter')
            ->add('students', EntityType::class,[
                 'class'=>Student::class,
                 'choice_label' => function($std){
                    return 'Name : '.$std->getUser()->getName().'  '.
                          ' ,C.I.N : '.$std->getStdperinfo()->getCin().'  '.
                          ' ,Filière : '.$std->getStdchoice()->getBactype();
                 },
                  'multiple' => true,
                  'expanded' => true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');
    },

            ] )
            ->add('classes', EntityType::class,[
                 'class'=>Classe::class,
                 'choice_label' => 'name',
                  'multiple' => true,
                  'expanded' => true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');
    },

            ])
            ->add('submit', SubmitType::class,[
      'attr'=>['class'=>'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prof::class,
        ]);
    }
}
