<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email :",
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'first_options'  => [
                    'label' => 'Mot de passe : ',
                    'attr' => ['class' => 'form-control'],
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => ['class' => 'form-control form-label']
                ],
            ])
            ->add('username', TextType::class, [
                'label' => "Pseudonyme :",
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('register', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn btn-primary w-100'],
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
