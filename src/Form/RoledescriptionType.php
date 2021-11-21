<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Ce formulaire permet de modifier le rôle de l'utilisateur qui apparaïtront dans les commentaires.
 */
class RoledescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roledescription', TextType::class, [
                'required' => false,
                'label' => 'Intitulé (optionnel)',
                'attr' => [
                    'placeholder' => 'Maître de stage'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "changer l'intitulé",
                'attr' => [
                    'class' => 'btn-secondary',
                        
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
