<?php

namespace App\Repository;

use App\Entity\LikeWall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LikeWall|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeWall|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeWall[]    findAll()
 * @method LikeWall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeWallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeWall::class);
    }

    // /**
    //  * @return LikeWall[] Returns an array of LikeWall objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LikeWall
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
