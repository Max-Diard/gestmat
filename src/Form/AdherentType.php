<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthdate')
            ->add('comments1')
            ->add('comments2')
            ->add('comments3')
            ->add('phone_mobile')
            ->add('phone_home')
            ->add('phone_work')
            ->add('phone_comments')
            ->add('profession')
            ->add('size')
            ->add('weight')
            ->add('permis')
            ->add('car')
            ->add('announcement_publish')
            ->add('announcement_presentation')
            ->add('announcement_free')
            ->add('announcement_date_free')
            ->add('announcement_newspaper')
            ->add('announcement_date_newspaper')
            ->add('owner')
            ->add('email')
            ->add('link_picture')
            ->add('link_contrat')
            ->add('link_information')
            ->add('link_picture_announcement')
            ->add('address_street')
            ->add('address_street2')
            ->add('address_zip_postal')
            ->add('address_town')
            ->add('child_girl')
            ->add('child_boy')
            ->add('child_dependent_girl')
            ->add('child_dependent_boy')
            ->add('search_age_min')
            ->add('search_age_max')
            ->add('search_single')
            ->add('search_divorced')
            ->add('search_windower')
            ->add('search_profession')
            ->add('search_region')
            ->add('search_more')
            ->add('search_accept_children')
            ->add('search_number_accept_children')
            ->add('status_matrimoniale')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}
