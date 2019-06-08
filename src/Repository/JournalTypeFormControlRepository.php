<?php

namespace App\Repository;

use App\Entity\JournalTypeFormControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalTypeFormControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalTypeFormControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalTypeFormControl[]    findAll()
 * @method JournalTypeFormControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalTypeFormControlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalTypeFormControl::class);
    }

    /**
     * Получить список предметов по id групи
     * @param mixed $group_id
     * @return JournalTypeFormControl
     */
    public function getSubjectsOnGroup($group_id){
        return $this->createQueryBuilder('tfc')
            ->leftJoin('tfc.subjects','s')
            ->leftJoin('s.group','g')
            ->where('g.id = s.group')
            ->andWhere('g.id = :group_id')
            ->setParameter('group_id',$group_id)
            ->getQuery()
            ->setMaxResults(1)
            ->execute();
    }



    // /**
    //  * @return JournalTypeFormControl[] Returns an array of JournalTypeFormControl objects
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
    public function findOneBySomeField($value): ?JournalTypeFormControl
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
