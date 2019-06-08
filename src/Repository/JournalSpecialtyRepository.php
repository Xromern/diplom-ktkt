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

    public function getGroupOnByTeacher(int $teacher_id):array {
      return  $this->createQueryBuilder('s')
            ->leftJoin('s.groups','g')
            ->leftJoin('g.subjects','sub')
            ->leftJoin('sub.mainTeacher','mt')
            ->leftJoin('g.curator','c')
            ->andWhere('mt.id = :id_teacher or c.id = :id_teacher')
            ->setParameter('id_teacher',$teacher_id)
            ->getQuery()
            ->execute();
    }

}
