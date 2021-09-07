<?php

namespace App\Repository;

use App\Entity\AdherentOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdherentOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdherentOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdherentOption[]    findAll()
 * @method AdherentOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdherentOption::class);
    }

    // /**
    //  * @return AdherentOption[] Returns an array of AdherentOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdherentOption
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
