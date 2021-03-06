<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $imageConstraints = [
        //     new File([
        //         'maxSize' => '1K',
        //         'mimeTypes' => [
        //             'image/jpeg',
        //         ],
        //         'mimeTypesMessage' => 'Please upload a valid image. '
        //     ])
        // ];

        $builder
            ->add('file', FileType::class, [
                'label' => 'Ajoutez une bannière d\'article',
                'required' => true,
                // 'constraints' => $imageConstraints

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
