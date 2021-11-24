<?php

namespace App\Repository;

use App\Entity\ReportTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportTopic[]    findAll()
 * @method ReportTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportTopic::class);
    }

    // /**
    //  * @return ReportTopic[] Returns an array of ReportTopic objects
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
    public function findOneBySomeField($value): ?ReportTopic
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
