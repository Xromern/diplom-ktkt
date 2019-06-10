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
    public function getGroupByAlis($alis){
        return $this->createQueryBuilder('g')
            ->where("g.id = :group_alis or g.alis_en = :group_alis")
            ->setParameter('group_alis',$alis)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param mixed $group_name
     * @param mixed $group_id
     * @return JournalGroup
     */
    public function checkGroupName($group_name, $group_id){
        return $this->createQueryBuilder('g')
            ->where('g.name = :group_name')
            ->andWhere('g.id != :group_id')
            ->setParameter('group_name',$group_name)
            ->setParameter('group_id',$group_id)
            ->getQuery()
            ->execute();
    }

    /**
     * @param mixed $curator_id
     * @param mixed $group_id
     * @return JournalGroup
     */
    public function checkGroupCurator($curator_id, $group_id){
        return $this->createQueryBuilder('g')
            ->leftJoin('g.curator', 'gt')
            ->andWhere('gt.id = :curator_id')
            ->andWhere('g.id != :group_id')
            ->setParameter('curator_id',$curator_id)
            ->setParameter('group_id',$group_id)
            ->getQuery()
            ->execute();
    }

}
