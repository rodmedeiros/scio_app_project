<?php

namespace App\Form;

use App\Entity\SchoolAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolAddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state')
            ->add('city')
            ->add('district')
            ->add('street')
            ->add('number')
            ->add('complement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SchoolAddress::class,
        ]);
    }
}
