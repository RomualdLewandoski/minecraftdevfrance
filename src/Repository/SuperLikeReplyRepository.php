<?php

namespace App\Repository;

use App\Entity\SuperLikeReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuperLikeReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperLikeReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperLikeReply[]    findAll()
 * @method SuperLikeReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperLikeReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperLikeReply::class);
    }

    // /**
    //  * @return SuperLikeReply[] Returns an array of SuperLikeReply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuperLikeReply
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
