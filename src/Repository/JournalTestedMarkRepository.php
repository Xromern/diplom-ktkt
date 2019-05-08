<?php

namespace App\Repository;

use App\Entity\JournalTestedMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalTestedMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalTestedMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalTestedMark[]    findAll()
 * @method JournalTestedMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalTestedMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalTestedMark::class);
    }

    // /**
    //  * @return JournalTestedMark[] Returns an array of JournalTestedMark objects
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
    public function findOneBySomeField($value): ?JournalTestedMark
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
