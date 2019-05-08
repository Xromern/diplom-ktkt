<?php

namespace App\Repository;

use App\Entity\JournalMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalMark[]    findAll()
 * @method JournalMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalMark::class);
    }

    // /**
    //  * @return JournalMark[] Returns an array of JournalMark objects
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
    public function findOneBySomeField($value): ?JournalMark
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
