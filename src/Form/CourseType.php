<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\Course;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CourseType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre de cours", "Donnez un titre de votre cours ...") )
            ->add('picture', UrlType::class, $this->getConfiguration("Image de cours", "Donnez une image pour attirez l'attention ..."))
            ->add('content', TextareaType::class,  $this->getConfiguration("Contenu de cours", "Donnez un contenu de cours ..."))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
