<?php

namespace App\Repository;

use App\Entity\Adverticment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Adverticment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adverticment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adverticment[]    findAll()
 * @method Adverticment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdverticmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Adverticment::class);
    }

    // /**
    //  * @return Adverticment[] Returns an array of Adverticment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Adverticment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
