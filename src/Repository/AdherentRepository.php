<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\AdherentOption;
use App\Entity\Agence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */
    public function findByLastId()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */
    public function findByGenre($value)
    {
        return $this->createQueryBuilder('a')
            ->join(AdherentOption::class, 'o')
            ->andWhere('a.genre = o.id')
            ->andWhere('o.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */
    public function findByGenreAgences($value, $agences)
    {
        return $this->createQueryBuilder('ad')
            ->join(AdherentOption::class, 'o')
            ->join(Agence::class, 'ag')
            ->andWhere('ad.genre = o.id')
            ->andWhere('o.name = :val')
            ->andWhere('ad.agence = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $agences)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Adherent
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
