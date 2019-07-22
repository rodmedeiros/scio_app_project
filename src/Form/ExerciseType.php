<?php

namespace App\Form;

use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseType extends TeachingResourcesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('description', null, [
                'label' => 'Insira a questão',
                'attr' => ['class' => 'summernote-exercise']
            ])
        ;


        $builder
            ->add('objective', null,['label' => 'Objetiva'])
            ->add('aswer', TextareaType::class,[
                'label' => 'Resposta',
                'attr'=>[
                    'rows'=>8
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
