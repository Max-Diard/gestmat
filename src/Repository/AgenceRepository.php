<?php

namespace App\Repository;

use App\Entity\Agence;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agence[]    findAll()
 * @method Agence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agence::class);
    }

    /**
     * @return Agence[] Returns an array of Agence objects
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
    
    //Je crois que ça ne sert nul part à revoir
     /**
     * @return Agence[] Returns an array of Agence objects
     */
    public function findByUser($value)
    {
        return $this->createQueryBuilder('a')
            ->join(User::class, 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->orderBy('a.name','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //Pour trouver les autres agences
    /**
     * @return Agence[] Returns an array of Agence objects
     */
    public function findOtherAgence($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id != :val')
            ->setParameter('val', $value)
            ->orderBy('a.name','ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /*
    public function findOneBySomeField($value): ?Agence
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
