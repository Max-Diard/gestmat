<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la donnée'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Le type de donnée',
                'choices' => [
                    'Status Matrimoniale' => 'status_matrimoniale',
                    'Status de rencontre' => 'status_meet',
                    'Type de logement' => 'lodging',
                    'Type de propriétaire' => 'owner',
                    'Type de fumeur' => 'smoking',
                    'Type de cheveux' => 'hair',
                    'Type d\'yeux' => 'eyes',
                    'Type de diplôme' => 'instruction',
                    'Sexe' => 'genre',
                    'Type de paiement' => 'type_payment',
                    'Préférence de contact' => 'preference_contact',
                    'Signe Astrologique' => 'zodiaque'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdherentOption::class,
        ]);
    }
}
