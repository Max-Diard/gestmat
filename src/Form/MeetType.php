<?php

namespace App\Form;

use App\Entity\AdherentOption;
use App\Entity\Meet;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('returnAt_woman', DateType::class, [
                'years' => range(date('Y'), date('Y') + 100),
                'required' => false
            ])
            ->add('returnAt_man', DateType::class, [
                'years' => range(date('Y'), date('Y') + 100),
                'required' => false
            ])
            ->add('comments_woman', TextareaType::class, [
                'required' => false
            ])
            ->add('comments_man', TextareaType::class, [
                'required' => false
            ])
            ->add('status_meet_woman', EntityType::class, [
                'class' => AdherentOption::class,
                'required' => false,
                'label' => 'Statut de rencontre Femme',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'status_meet');
                },
            ])
            ->add('status_meet_man', EntityType::class, [
                'class' => AdherentOption::class,
                'required' => false,
                'label' => 'Statut de rencontre Homme',
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->andWhere('s.type = :val')
                        ->setParameter('val', 'status_meet');
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meet::class,
        ]);
    }
}
