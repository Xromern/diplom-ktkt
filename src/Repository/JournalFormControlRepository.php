<?php

namespace App\Repository;

use App\Entity\JournalFormControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalFormControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalFormControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalFormControl[]    findAll()
 * @method JournalFormControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalFormControlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalFormControl::class);
    }

    // /**
    //  * @return JournalFormControl[] Returns an array of JournalFormControl objects
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
    public function findOneBySomeField($value): ?JournalFormControl
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
