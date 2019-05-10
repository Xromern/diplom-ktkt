<?php

namespace App\Repository;

use App\Entity\JournalGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JournalGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalGroup[]    findAll()
 * @method JournalGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalGroup::class);
    }

    /**
     * @param mixed $alis
     * @return JournalGroup
     */
    public function getCuratorByAlis($alis){

        return $this->createQueryBuilder('g')
            ->where("g.id = :group_alis or g.alis_en = :group_alis")
            ->setParameter('group_alis',$alis)
            ->getQuery()
            ->execute()[0];

    }


    // /**
    //  * @return JournalGroup[] Returns an array of JournalGroup objects
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
    public function findOneBySomeField($value): ?JournalGroup
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
