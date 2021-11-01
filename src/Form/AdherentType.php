<?php

namespace App\Form;

use App\Entity\Adherent;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $agences = $options['agences'];
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'name_input' => 'Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'name_input' => 'Nom'
                ]
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'years'=> range(date('Y'), date('Y') - 100),
                'format' => 'dd-MM-yyyy',
                'required' => true,
                'attr' => [
                    'name_input' => 'Date de naissance'
                ]
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
                'label' => 'N° tél. portable',
                'required' => true,
                'attr' => [
                    'type' => 'number',
                    'pattern' => '^0[0-9]([-. ]?\d{2}){4}[-. ]?$',
                    'title' => '09 09 09 09 09 ou 0909090909',
                    'class' => 'phone_form',
                    'name_input' => 'N° tél. portable'
                ]
            ])
            ->add('phone_home', TelType::class, [
                'label' => 'N° tél. fixe',
                'required' => false,
                'attr' => [
                    'type' => 'number',
                    'pattern' => '^0[0-9]([-. ]?\d{2}){4}[-. ]?$',
                    'title' => '09 09 09 09 09 ou 0909090909',
                    'class' => 'phone_form'
                ]
            ])
            ->add('phone_work', TelType::class, [
                'label' => 'N° tél. travail',
                'required' => false,
                'attr' => [
                    'type' => 'number',
                    'pattern' => '^0[0-9]([-. ]?\d{2}){4}[-. ]?$',
                    'title' => '09 09 09 09 09 ou 0909090909',
                    'class' => 'phone_form'
                ]
            ])
            ->add('phone_comments', TextType::class, [
                'label' => 'Commentaires tél.',
                'required' => false
            ])
            ->add('profession', TextType::class, [
                'label' => 'Profession',
                'required' => true,
                'attr' => [
                    'name_input' => 'Profession'
                ]
            ])
            ->add('size', TextType::class, [
                'label' => 'Taille',
                'required' => true,
                'attr'=> [
                    'name_input' => 'Taille'
                ]
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Poids',
                'required' => true,
                'attr' => [
                    'name_input' => 'Poids'
                ]
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
                'label' => 'Annonce publiée',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('announcement_presentation', CKEditorType::class, [
                'label' => 'Annonce Fiche Présentation',
                'required' => true,
                'attr' => [
                    'name_input' => 'Annonce Fiche Présentation'
                ]
            ])
            ->add('announcement_free', CKEditorType::class, [
                'label' => 'Annonce Site',
                'required' => false,
            ])
            ->add('announcement_date_free', DateType::class, [
                'label' => 'Date d\'annonce gratuite',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years'=> range(date('Y') -10 , date('Y') + 50)
            ])
            ->add('announcement_newspaper', CKEditorType::class, [
                'label' => 'Annonce Journal',
                'required' => false,
            ])
            ->add('announcement_date_newspaper', DateType::class, [
                'label' => 'Date d\'annonce journal',
                'required' => false,
                'format' => 'dd-MM-yyyy',
                'years'=> range(date('Y') - 10, date('Y') + 50),
            ])
            ->add('owner', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => "Statut d'occupation du logement",
                'query_builder' => function (EntityRepository $repository) use($options){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'owner');
                }
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'name_input' => 'Email'
                ]
            ])
            ->add('link_picture', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'accept' => ".png,.jpg,.jpeg"
                ]
            ])
            ->add('link_contract', FileType::class, [
                'label' => 'Contrat',
                'required' => false,
                'data_class' => null
            ])
            ->add('link_information', FileType::class, [
                'label' => 'Fiche Renseignements',
                'required' => false,
                'data_class' => null
            ])
            ->add('link_picture_announcement', FileType::class, [
                'label' => 'Photo Annonce',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'accept' => ".png,.jpg,.jpeg"
                ]
            ])
            ->add('address_street', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => [
                    'name_input' => 'Adresse'
                ]
            ])
            ->add('address_street2', TextType::class, [
                'label' => 'Adresse 2',
                'required' => false
            ])
            ->add('address_zip_postal', NumberType::class ,[
                'label' => 'Code postal',
                'required' => true,
                'attr' => [
                    'name_input' => 'Code Postal'
                ]
            ])
            ->add('address_town', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'attr' => [
                    'name_input' => 'Ville'
                ]
            ])
            ->add('child_girl', NumberType::class, [
                'label' => 'Fille',
                'required' => true,
                'attr' => [
                    'name_input' => 'Fille'
                ]
            ])
            ->add('child_boy', NumberType::class, [
                'label' => 'Garçon',
                'required' => true,
                'attr' => [
                    'name_input' => 'Garçon'
                ]
            ])
            ->add('child_dependent_girl', NumberType::class, [
                'label' => 'Fille à charge',
                'required' => true,
                'attr' => [
                    'name_input' => 'Fille à charge'
                ]
            ])
            ->add('child_dependent_boy', NumberType::class, [
                'label' => 'Garçon à charge',
                'required' => true,
                'attr' => [
                    'name_input' => 'Garçon à charge'
                ]
            ])
            ->add('search_age_min', NumberType::class, [
                'label' => 'Âge minimum',
                'required' => true,
                'attr' => [
                    'name_input' => 'Âge minimum'
                ]
            ])
            ->add('search_age_max', NumberType::class, [
                'label' => 'Âge maximum',
                'required' => true,
                'attr' => [
                    'name_input' => 'Âge maximum'
                ]
            ])
            ->add('search_single', ChoiceType::class, [
                'label' => 'Recherche célibataire', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_divorced', ChoiceType::class, [
                'label' => 'Recherche divorcé(e)', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_widower', ChoiceType::class, [
                'label' => 'Recherche Veuf(ve)', 
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('search_instruction', TextType::class, [
                'label' => "Niveau d'étude",
                'required' => true,
                'attr' => [
                    'name_input' => 'Recherche Niveau d\'étude'
                ]
            ])
            ->add('search_profession', TextType::class, [
                'label' => 'Profession',
                'required' => false
            ])
            ->add('search_region', TextType::class, [
                'label' => 'Région',
                'required' => false
            ])
            ->add('search_more', TextareaType::class, [
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
                'label' => 'Nombre d\'enfants acceptés',
                'required' => true,
                'attr' => [
                    'name_input' => 'Nombre d\'enfants acceptés'
                ]
            ])
            ->add('status_matrimoniale', EntityType::class, [
                'class' => AdherentOption::class,
                'label' => 'Statut matrimonial',
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
            ->add('instruction', TextType::class, [
                'label' => 'Niveau d\'étude',
                'required' => true,
                'attr' => [
                    'name_input' => 'Niveau d\'étude'
                ]
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
                'label' => 'Signe astrologique',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->orderBy('s.id' , 'desc')
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
                'label' => 'Montant TTC',
                'error_bubbling' => true,
                'required' => true,
                'attr' => [
                    'name_input' => 'Montant TTC',
                    'class' => 'amount_form'
                ]
            ])
            ->add('contract_comments', CKEditorType::class, [
                'label' => 'Commentaires',
                'required' => false,
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
                'label' => 'Enregistrer'
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
