<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
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
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'agence'
            ])
            ->add('phone_number', TelType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('startedAt', DateType::class, [
                'label'  => 'Date de début du contrat',
                'years'=> range(date('Y'), date('Y')+100),
                'format' => 'dd-MM-yyyy',
            ])
            ->add('endingAt', DateType::class, [
                'label'  => 'Date de fin du contrat',
                'years'=> range(date('Y'), date('Y')+100),
                'format' => 'dd-MM-yyyy',
            ])
            ->add('link_picture', TextType::class, [
                'label' => 'Lien de votre image'
            ])
            ->add('address_street', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('address_street2', TextType::class, [
                'label' => 'Suite addresse',
                'required' => false
            ])
            ->add('address_zip_postal', NumberType::class, [
                'label' => 'Code Postal'
            ])
            ->add('address_town', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}
