<?php

namespace App\Repository;

use App\Entity\JournalFormControlMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalFormControlMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalFormControlMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalFormControlMark[]    findAll()
 * @method JournalFormControlMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalFormControlMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalFormControlMark::class);
    }

    // /**
    //  * @return JournalFormControlMark[] Returns an array of JournalFormControlMark objects
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
    public function findOneBySomeField($value): ?JournalFormControlMark
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
