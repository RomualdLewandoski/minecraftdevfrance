<?php

namespace App\Repository;

use App\Entity\LikeReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LikeReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeReply[]    findAll()
 * @method LikeReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeReply::class);
    }

    // /**
    //  * @return LikeReply[] Returns an array of LikeReply objects
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
    public function findOneBySomeField($value): ?LikeReply
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
