<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Student;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\ProfController;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;



class NoteType extends AbstractType
{   public $clt;
    function __construct(ProfController $ctl)
    {
        $this->ctl = $ctl;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder/*->add('students', EntityType::class,[
                 'class'=>Student::class,
                 'choice_label' => 'user.name',
                 'expanded'=>true,
                 'multiple'=>true,
                 'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.classe', 'ASC');
    },
                 
                  ]
                )*/
                ->add('note')
                 ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
