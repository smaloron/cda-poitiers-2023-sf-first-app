<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\Transformer\TagDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{

    public function __construct(private TagDataTransformer $tagTransformer){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => User::class,
                'choice_label' => 'nickName'
            ])
            ->add('theme', EntityType::class, [
                'label' => 'Thème',
                'class' => Theme::class,
                'choice_label' => 'themeName'
            ])
            ->add('tags', TextType::class, [
                'label' => 'Liste des tags',
                'help' => 'Liste de tags séparés par une virgule'])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['rows' => '5']
            ])
            ->add('Valider', SubmitType::class)
        ;

        $builder->get('tags')->addModelTransformer($this->tagTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}