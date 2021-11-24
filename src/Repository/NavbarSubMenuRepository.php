<?php

namespace App\Repository;

use App\Entity\NavbarSubMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NavbarSubMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavbarSubMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavbarSubMenu[]    findAll()
 * @method NavbarSubMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavbarSubMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavbarSubMenu::class);
    }

    // /**
    //  * @return NavbarSubMenu[] Returns an array of NavbarSubMenu objects
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
    public function findOneBySomeField($value): ?NavbarSubMenu
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
