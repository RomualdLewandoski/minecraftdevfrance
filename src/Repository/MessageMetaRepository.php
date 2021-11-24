<?php

namespace App\Repository;

use App\Entity\MessageMeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageMeta|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageMeta|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageMeta[]    findAll()
 * @method MessageMeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageMetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageMeta::class);
    }

    // /**
    //  * @return MessageMeta[] Returns an array of MessageMeta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageMeta
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
