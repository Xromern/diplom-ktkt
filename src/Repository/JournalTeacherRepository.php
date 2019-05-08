<?php

namespace App\Repository;

use App\Entity\JournalTeacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalTeacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalTeacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalTeacher[]    findAll()
 * @method JournalTeacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalTeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalTeacher::class);
    }

    // /**
    //  * @return JournalTeacher[] Returns an array of JournalTeacher objects
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
    public function findOneBySomeField($value): ?JournalTeacher
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
