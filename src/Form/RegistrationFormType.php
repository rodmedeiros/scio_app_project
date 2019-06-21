<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextTypeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['attr'=>[
                'class'=>'form-control',
                'required' => true
            ]])
            ->add('email', EmailType::class, ['attr'=>[
                'class'=>'form-control',
                'required' => true
                ]])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'label' => 'Senha',
                'first_options'  => array('label' => 'Senha', 'attr'=>['class'=>'form-control']),
                'second_options' => array('label' => 'Repetir Senha', 'attr'=>['class'=>'form-control'],),
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, escolha uma senha',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Sua senha deve conter ao menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}



