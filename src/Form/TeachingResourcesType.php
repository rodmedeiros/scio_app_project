<?php

namespace App\Form;

use App\Entity\TeachingResources;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeachingResourcesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, [
                'label' => 'Descrição',
                'attr' => ['class' => 'summernote']
            ])
            ->add('content', null, [
                'label' => 'Conteúdo Associado',

            ])
            ->add('comment', null, [
                'label' => 'Adicionar Comentário',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TeachingResources::class,
        ]);
    }
}
