<?php

namespace App\Form;

use App\Entity\Paper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class PaperType extends TeachingResourcesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', null, [
                'label' => 'Título'
            ])
        ;

        parent::buildForm($builder, $options);

        $builder
            ->add('description', null, [
                'attr'=>[
                    'rows' => 1,
                    'type' => 'url',
                    'placeholder' => 'Se houver, Cole aqui o Link!',
                    'class' => 'blue',
                    'style' => 'color:#0000FF; text-decoration: underline;'
                ],
                'label' => 'Link'
            ])

            ->add('comment', null, [
                'label' => 'Adicionar Comentário',
                'attr' => ['class' => 'summernote']

            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paper::class,
        ]);
    }
}
