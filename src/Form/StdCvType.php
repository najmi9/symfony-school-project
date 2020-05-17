<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\StdCv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FlaotType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StdCvType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, $this->getConfiguration("Ville d'étude :", "Ville où vous avez étudier 1 bac :..."))
            ->add('school', TextType::class, $this->getConfiguration("Lycée d'étude :", "Lycée où vous avez obtenir 1 bac :..."))
            ->add('year', DateType::class, $this->getConfiguration("Date d'étude :", "Date quand vous avez obtenir 1 bac :..."))
            ->add('moyen', FloatType::class, $this->getConfiguration("Moyenne Générale :", "Moyenne Générale :..."))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StdCv::class,
        ]);
    }
}
