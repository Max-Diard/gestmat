<?php

namespace App\Form;

use App\Entity\Agence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DelegationAgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $agences = $options['agences'];
        $builder
            ->add('droit_agence', EntityType::class, [
                'class' => Agence::class, 
                'expanded' => true, 
                'multiple' => true,
                'label' => 'Délégation des droits :',
                'choices' => $agences, 
            ])
            // ->add('droit_agence', ChoiceType::class, [
            //     'label' => 'Délégation des droits :',
            //     'choice_label' => 'name',
            //     'choices' => $agences, 
            //     'attr' => [
            //         'value' => 'id'
            //     ]
            // ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
            'agences' => null
        ]);
    }
}
