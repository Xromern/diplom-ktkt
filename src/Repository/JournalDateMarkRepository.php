<?php

namespace App\Repository;

use App\Entity\JournalDateMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalDateMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalDateMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalDateMark[]    findAll()
 * @method JournalDateMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalDateMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalDateMark::class);
    }

    public function getPageSubject(int $subject_id){
        return  $this->createQueryBuilder('d')
            ->select('distinct(d.page) as page')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->setParameter('subject_id',20)
            ->orderBy('page','asc')
            ->getQuery()
            ->execute();

    }

    // /**
    //  * @return JournalDateMark[] Returns an array of JournalDateMark objects
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
    public function findOneBySomeField($value): ?JournalDateMark
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
