<?php

namespace App\Repository;

use App\Entity\JournalCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalCode[]    findAll()
 * @method JournalCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalCode::class);
    }

    // /**
    //  * @return JournalCode[] Returns an array of JournalCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JournalCode
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
