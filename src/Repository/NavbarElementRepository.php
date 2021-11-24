<?php

namespace App\Repository;

use App\Entity\NavbarElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NavbarElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavbarElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavbarElement[]    findAll()
 * @method NavbarElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavbarElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavbarElement::class);
    }



    // /**
    //  * @return NavbarElement[] Returns an array of NavbarElement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NavbarElement
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
