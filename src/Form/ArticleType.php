<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\Article;
use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        ->add('image', ImageType::class, [
            'mapped' => true,
            'label' => false,
        ])
        ->add('theme', EntityType::class, [
            'label' => 'Theme',
            'placeholder' => '-- Choisir un theme',
            'class' => Theme::class,
            'choice_label' => function (Theme $theme) {
                return strtoupper($theme->getName());
            },
            'required' => false
        ])
        
        ->add('submit', SubmitType::class)
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
