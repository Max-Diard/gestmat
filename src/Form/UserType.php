<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'L\'email de l\'utilisateur'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles:',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Technicien' => 'ROLE_TECH',
                    'Utilisateur' => 'ROLE_USER',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doit être identique',
                'first_name' => 'firstPassword',
                'first_options' => [
                    'label' => 'Mot de passe'
                ],
                'second_name' => 'secondPassword',
                'second_options' =>[
                    'label' => 'Confirmer le mot de passe'
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Le prénom de l\'utilisateur'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Le nom de l\'utilisateur'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
