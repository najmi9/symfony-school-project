<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('student', EntityType::class,
            [
                 'class'=>Student::class,
                 'choice_label' => function ($student)
                  {
                    $info =  $student->getStdperinfo();
                   
                    
                        return 'Name : '.$student->getUser()->getName().
                            ', '.' C.I.N : '.$info->getCin().', '.
                            ' Class : '.$student->getStdChoice()->getBactype();
                    
                   
                  },
            ])
        ->add('note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
