<?php

namespace App\Repository;

use App\Entity\JournalStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalStudent[]    findAll()
 * @method JournalStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalStudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalStudent::class);
    }

    // /**
    //  * @return JournalStudent[] Returns an array of JournalStudent objects
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
    public function findOneBySomeField($value): ?JournalStudent
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
