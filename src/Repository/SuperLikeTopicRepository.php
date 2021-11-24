<?php

namespace App\Repository;

use App\Entity\SuperLikeTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuperLikeTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperLikeTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperLikeTopic[]    findAll()
 * @method SuperLikeTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperLikeTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperLikeTopic::class);
    }

    // /**
    //  * @return SuperLikeTopic[] Returns an array of SuperLikeTopic objects
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
    public function findOneBySomeField($value): ?SuperLikeTopic
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
