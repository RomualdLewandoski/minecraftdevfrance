<?php

namespace App\Repository;

use App\Entity\ReportReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportReply[]    findAll()
 * @method ReportReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportReply::class);
    }

    // /**
    //  * @return ReportReply[] Returns an array of ReportReply objects
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
    public function findOneBySomeField($value): ?ReportReply
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
