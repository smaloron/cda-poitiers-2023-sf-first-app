<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dailyRate')
            ->add('address', AddressType::class)
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'skillName'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
