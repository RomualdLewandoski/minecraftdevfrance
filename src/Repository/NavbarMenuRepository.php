<?php

namespace App\Repository;

use App\Entity\NavbarMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NavbarMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavbarMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavbarMenu[]    findAll()
 * @method NavbarMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavbarMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavbarMenu::class);
    }

    public function getMenu(){
        return $this->createQueryBuilder('n')
            ->orderBy('n.position', "ASC")
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return NavbarMenu[] Returns an array of NavbarMenu objects
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
    public function findOneBySomeField($value): ?NavbarMenu
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
