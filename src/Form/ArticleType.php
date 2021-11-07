<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre de l\'article',
            'attr' => ['placeholder' => 'Titre de l\'article'],
            'required' => false,
        ])
        ->add('description', CKEditorType::class, [
            'label' => 'Description courte',
            'attr' => [
                'placeholder' => 'Descriptif'
            ],
            'required' => false
        ])
        ->add('theme', EntityType::class, [
            'label' => 'Theme',
            'placeholder' => '-- Choisir un theme',
            'class' => Theme::class,
            'choice_label' => function (Theme $theme) {
                return strtoupper($theme->getName());
            },
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
