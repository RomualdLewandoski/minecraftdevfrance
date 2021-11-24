<?php

namespace App\Repository;

use App\Entity\ReportWall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportWall|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportWall|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportWall[]    findAll()
 * @method ReportWall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportWallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportWall::class);
    }

    // /**
    //  * @return ReportWall[] Returns an array of ReportWall objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportWall
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
