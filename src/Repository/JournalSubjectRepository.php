<?php

namespace App\Repository;

use App\Entity\JournalSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalSubject[]    findAll()
 * @method JournalSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalSubjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalSubject::class);
    }

    // /**
    //  * @return JournalSubject[] Returns an array of JournalSubject objects
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
    public function findOneBySomeField($value): ?JournalSubject
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
