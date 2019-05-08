<?php

namespace App\Repository;

use App\Entity\JournalTested;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalTested|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalTested|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalTested[]    findAll()
 * @method JournalTested[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalTestedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalTested::class);
    }

    // /**
    //  * @return JournalTested[] Returns an array of JournalTested objects
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
    public function findOneBySomeField($value): ?JournalTested
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
