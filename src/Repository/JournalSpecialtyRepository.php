<?php

namespace App\Repository;

use App\Entity\JournalSpecialty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalSpecialty|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalSpecialty|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalSpecialty[]    findAll()
 * @method JournalSpecialty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalSpecialtyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalSpecialty::class);
    }

    // /**
    //  * @return JournalSpecialty[] Returns an array of JournalSpecialty objects
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
    public function findOneBySomeField($value): ?JournalSpecialty
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
