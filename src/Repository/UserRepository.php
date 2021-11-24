<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getTotalLike(User $user)
    {
        $count = 0;
        $walls = $user->getUserWalls();
        foreach ($walls as $wall) {
            $count += count($wall->getLiked());
        }
        $topics = $user->getTopics();
        foreach ($topics as $topic) {
            $count += count($topic->getLikes());
        }
        $replies = $user->getReplies();
        foreach ($replies as $reply) {
            $count += count($reply->getLikes());
        }
        return $count;
    }

    public function getTotalAdminLike(User $user){
        $count = 0;
        $topics = $user->getTopics();
        foreach ($topics as $topic) {
            $count += count($topic->getSuperLikeTopics());
        }
        $replies = $user->getReplies();
        foreach ($replies as $reply) {
            $count += count($reply->getSuperLikeReplies());
        }
        return $count;
    }

    public function getWhereUsernameLike(String $string)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :string')
            ->orWhere('u.username LIKE :string1')
            ->orWhere('u.username LIKE :string2')
            ->orWhere('u.username LIKE :string3')
            ->setParameter(':string', $string)
            ->setParameter(':string1', '%' . $string)
            ->setParameter(':string2', '%' . $string . '%')
            ->setParameter(':string3', $string . '%')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
