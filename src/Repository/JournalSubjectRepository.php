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

    /**
     * @param mixed $groupAlis
     * @param mixed $subjectAlis
     * @return JournalGroup
     */
    public function getSubjectByAlis($groupAlis,$subjectAlis){
        return $this->createQueryBuilder('s')
            ->where("s.id = :subjectAlis or s.alis_en = :subjectAlis")
            ->setParameter('subjectAlis',$subjectAlis)
            ->leftJoin('s.group','g')
            ->andWhere("g.id = :groupAlis or g.alis_en = :groupAlis")
            ->setParameter('groupAlis',$groupAlis)
            ->getQuery()
            ->execute()[0];
    }

    public function checkForUniqueness($subjectName,$group_id,$subject_id = 0){
        return $this
            ->createQueryBuilder('s')
            ->leftJoin('s.group','g')
            ->andWhere('g.id = :group_id')
            ->andWhere('s.name = :name')
            ->andWhere('s.id != :subject_id')
            ->setParameter('group_id',$group_id)
            ->setParameter('name',$subjectName)
            ->setParameter('subject_id',$subject_id)
            ->getQuery()
            ->execute();

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
