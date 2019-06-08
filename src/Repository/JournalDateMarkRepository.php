<?php

namespace App\Repository;

use App\Entity\JournalDateMark;
use App\Service\Journal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function getCountPage(int $subject_id){
        return count($this->createQueryBuilder('d')
            ->select('distinct(d.page) as page')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->setParameter('subject_id',$subject_id)
            ->orderBy('page','asc')
            ->getQuery()
            ->execute());
    }

    public function getOnByPage(int $subject_id,int $page){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.page = :page')
            ->setParameter('subject_id',$subject_id)
            ->setParameter('page',$page)
            ->getQuery()
            ->execute();

    }
    /**
     *  Проверка даты на пустоту
     */
    public function checkDateOnEmpty(JournalDateMark $journalDateMark){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere("(d.id > :date_id AND d.date IS NOT NULL) ")
            ->setParameter('date_id',$journalDateMark->getId())
            ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
            ->getQuery()
            ->execute();
    }

    /**
     *  Проверка на то что дата не меньше предыдущих
     */
    public function checkDateOnMin(JournalDateMark $journalDateMark,$date){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.date >= :date and d.id < :date_id')//выбираю  все дати которые >= текущей и стоят до этой даты
            ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
            ->setParameter('date',$date)
            ->setParameter('date_id',$journalDateMark->getId())
            ->getQuery()
            ->execute();
    }

    /**
     *  Проверка на то что дата не больше последующих
     */
    public function checkDateOnMax(JournalDateMark $journalDateMark,$date){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.date <= :date and d.id > :date_id')
            ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
            ->setParameter('date',$date)
            ->setParameter('date_id',$journalDateMark->getId())
            ->getQuery()
            ->execute();
    }

    /**
     *  Проверка на то что день не пропущен
     */
    public function checkDateOnSkip(JournalDateMark $journalDateMark){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere("(d.id < :date_id AND d.date IS NULL) ")
            ->setParameter('date_id',$journalDateMark->getId())
            ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
            ->getQuery()
            ->execute();
    }

}
