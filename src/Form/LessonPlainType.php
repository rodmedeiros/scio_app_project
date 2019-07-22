<?php

namespace App\Form;

use App\Entity\LessonPlain;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class LessonPlainType extends TeachingResourcesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
//            ->add('imageFile',FileType::class, [
//                'label' => 'Brochure (PDF file)',
//
//                // unmapped means that this field is not associated to any entity property
//                'mapped' => false,
//
//                // make it optional so you don't have to re-upload the PDF file
//                // everytime you edit the Product details
//                'required' => false,
//
//                // unmapped fields can't define their validation using annotations
//                // in the associated entity, so you can use the PHP constraint classes
//                'constraints' => [
//                    new File([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/x-pdf',
//                            'application/msword',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid document',
//                    ])
//                ],
//            ])

            ->add('title', null, [
                'label' => 'Título'
            ])
            ->add('imageFile',FileType::class, [
            ]);
        ;

        parent::buildForm($builder, $options);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LessonPlain::class,
        ]);
    }
}
