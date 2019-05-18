<?php

namespace App\Repository;

use App\Entity\JournalGradingSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalGradingSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalGradingSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalGradingSystem[]    findAll()
 * @method JournalGradingSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalGradingSystemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalGradingSystem::class);
    }

    // /**
    //  * @return JournalGradingSystem[] Returns an array of JournalGradingSystem objects
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
    public function findOneBySomeField($value): ?JournalGradingSystem
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
