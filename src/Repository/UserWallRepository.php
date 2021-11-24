<?php

namespace App\Repository;

use App\Entity\UserWall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserWall|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWall|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWall[]    findAll()
 * @method UserWall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWall::class);
    }

    // /**
    //  * @return UserWall[] Returns an array of UserWall objects
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
    public function findOneBySomeField($value): ?UserWall
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
