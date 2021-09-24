<?php

namespace App\Form;

use App\Entity\Adherent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $agences = $options['agences'];
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'years'=> range(date('Y'), date('Y') - 100),
                'format' => 'dd-MM-yyyy'
            ])
            ->add('comments1', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false
            ])
            ->add('comments2', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false
            ])
            ->add('comments3', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false
            ])
            ->add('phone_mobile', TelType::class, [
                'label' => 'Numéro de téléphone portable'
            ])
            ->add('phone_home', TelType::class, [
                'label' => 'Numéro de téléphone fixe',
                'required' => false
            ])
            ->add('phone_work', TelType::class, [
                'label' => 'Numéro de téléphone travail',
                'required' => false
            ])
            ->add('phone_comments', TextType::class, [
                'label' => 'Commentaire téléphone',
                'required' => false
            ])
            ->add('profession', TextType::class, [
                'label' => 'Profession'
            ])
            ->add('size', NumberType::class, [
                'label' => 'Taille'
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids'
            ])
            ->add('permis', ChoiceType::class, [
                'label' => 'Permis', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('car', ChoiceType::class, [
                'label' => 'Voiture', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('announcement_publish', ChoiceType::class, [
                'label' => 'Annonce publié', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('announcement_presentation', TextareaType::class, [
                'label' => 'Annonce Présentation',
                'required' => false
            ])
            ->add('announcement_free', TextareaType::class, [
                'label' => 'Annonce Présentation Gratuite',
                'required' => false
            ])
            ->add('announcement_date_free', DateType::class, [
                'label' => 'Date d\'annonce gratuite',
                'required' => false,
                'format' => 'dd-MM-yyyy'
            ])
            ->add('announcement_newspaper', TextareaType::class, [
                'label' => 'Annonce Présentation Journal',
                'required' => false
            ])
            ->add('announcement_date_newspaper', DateType::class, [
                'label' => 'Date d\'annonce journal',
                'required' => false,
                'format' => 'dd-MM-yyyy'
            ])
            ->add('owner', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Propriétaire',
                'query_builder' => function (EntityRepository $repository) use($options){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'owner');
                },
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('link_picture', FileType::class, [
                'label' => 'Lien de photo de l\'adhérent',
                'required' => false,
                'data_class' => null
            ])
            ->add('link_contract', FileType::class, [
                'label' => 'Lien du contrat de l\'adhérent',
                'required' => false,
                'data_class' => null
            ])
            ->add('link_information', FileType::class, [
                'label' => 'Lien information de l\'adhérent',
                'required' => false,
                'data_class' => null
            ])
            ->add('link_picture_announcement', FileType::class, [
                'label' => 'Lien photo annonce de l\'adhérent',
                'required' => false,
                'data_class' => null
            ])
            ->add('address_street', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('address_street2', TextType::class, [
                'label' => 'Adresse 2',
                'required' => false
            ])
            ->add('address_zip_postal', NumberType::class ,[
                'label' => 'Code postal'
            ])
            ->add('address_town', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('child_girl', NumberType::class, [
                'label' => 'Fille'
            ])
            ->add('child_boy', NumberType::class, [
                'label' => 'Garçon'
            ])
            ->add('child_dependent_girl', NumberType::class, [
                'label' => 'Fille à charge'
            ])
            ->add('child_dependent_boy', NumberType::class, [
                'label' => 'Garçon à charge'
            ])
            ->add('search_age_min', NumberType::class, [
                'label' => 'Age Minimum'
            ])
            ->add('search_age_max', NumberType::class, [
                'label' => 'Age Maximum'
            ])
            ->add('search_single', ChoiceType::class, [
                'label' => 'Recherche Célibataire', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_divorced', ChoiceType::class, [
                'label' => 'Recherche divorcée', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_windower', ChoiceType::class, [
                'label' => 'Recherche Veuf(ve)', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_instruction', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Diplôme',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'instruction');
                },
            ])
            ->add('search_profession', TextType::class, [
                'label' => 'Profession',
                'required' => false
            ])
            ->add('search_region', TextType::class, [
                'label' => 'Région',
                'required' => false
            ])
            ->add('search_more', TextAreaType::class, [
                'label' => 'Autre',
                'required' => false
            ])
            ->add('search_accept_children', ChoiceType::class, [
                'label' => 'Accepte les enfants', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_number_accept_children', NumberType::class, [
                'label' => 'Nombre d\'enfant accepté'
            ])
            ->add('status_matrimoniale', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Status Matrimoniale',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'status_matrimoniale');
                },
            ])
            ->add('status_meet', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Status Relation',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'status_meet');
                },
            ])
            ->add('instruction', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Diplôme',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'instruction');
                },
            ])
            ->add('lodging', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Habitat',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'lodging');
                },
            ])
            ->add('smoking', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Type de fumeur',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'smoking');
                },
            ])
            ->add('hair', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Cheveux',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'hair');
                },
            ])
            ->add('zodiaque', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Signe Astrologique',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'zodiaque');
                },
            ])
            ->add('eyes', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Couleur des yeux',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'eyes');
                },
            ])
            ->add('genre', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Sexe',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'genre');
                },
            ])
            ->add('preference_contact', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Préférence de contact',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'preference_contact');
                },
            ])
            ->add('contract_date', DateType::class, [
                'label' => 'Date contrat',
                'years'=> range(date('Y'), date('Y') + 100),
                'format' => 'dd-MM-yyyy'
            ])
            ->add('contract_startedAt', DateType::class, [
                'label' => 'Date début de contrat',
                'years'=> range(date('Y'), date('Y') + 100),
                'format' => 'dd-MM-yyyy'
            ])
            ->add('contract_endingAt', DateType::class, [
                'label' => 'Date fin de contrat',
                'years'=> range(date('Y'), date('Y') + 100),
                'format' => 'dd-MM-yyyy'
            ])
            ->add('contract_amount', NumberType::class, [
                'label' => 'Montant TTC'
            ])
            ->add('contract_comments', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false
            ])
            ->add('type_payment', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Type de réglement',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'type_payment');
                },
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'label' => 'Agence',
                'choices' => $agences
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'agences' => null
        ]);
    }
}
