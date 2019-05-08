<?php

namespace App\Repository;

use App\Entity\JournalTypeMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalTypeMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalTypeMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalTypeMark[]    findAll()
 * @method JournalTypeMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalTypeMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalTypeMark::class);
    }

    // /**
    //  * @return JournalTypeMark[] Returns an array of JournalTypeMark objects
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
    public function findOneBySomeField($value): ?JournalTypeMark
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
