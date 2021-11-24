<?php

namespace App\Repository;

use App\Entity\UserTrophy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTrophy|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTrophy|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTrophy[]    findAll()
 * @method UserTrophy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTrophyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTrophy::class);
    }

    // /**
    //  * @return UserTrophy[] Returns an array of UserTrophy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTrophy
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
