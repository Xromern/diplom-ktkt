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

    public function getOnByPage(int $subject_id,$page=null){
        $d = $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id');
            if($page !=null){
                $d = $d->andWhere('d.page = :page')
                    ->setParameter('page',$page);
            }
            $d = $d->setParameter('subject_id',$subject_id)
            ->getQuery()
            ->execute();
            return $d;

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

    public function getDistinctOnByGroup($group_id){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->leftJoin('s.group','g')
            ->select('date_format(d.date, \'%Y-%m\') as dateFormat')
            ->andWhere('g.id = :group_id')
            ->groupBy('dateFormat')
            ->orderBy('dateFormat','desc')
            ->setParameter('group_id',$group_id)
            ->getQuery()
            ->execute();
    }

    public function getDateForm6($group_id,$date){
        return $this->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->leftJoin('s.group','g')
            ->andWhere('date_format(d.date, \'%Y-%m\') = :date')
            ->andWhere('g.id = :group_id')
            ->setParameter('group_id',$group_id)
            ->setParameter('date',$date)
            ->getQuery()
            ->execute();
    }

    public function getRangeDateOnBayStudent($student_id)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.marks','m')
            ->leftJoin('m.student','stud')
            ->andWhere('stud.id = :student_id')
            ->setParameter('student_id',$student_id)
            ->addSelect('max(date_format(d.date, \'%Y-%m-%d\')) as dateMax')
            ->addSelect('min(date_format(d.date, \'%Y-%m-%d\')) as dateMin')
            ->getQuery()
            ->execute();
    }

    public function getDateOnByRange(int $student_id,$subject_id,$dateMax,$dateMin){
        $query = $this->createQueryBuilder('d')
            ->leftJoin('d.subject','sub')
            ->leftJoin('d.marks','m')
            ->leftJoin('m.student','stud')
            ->andWhere('stud.id = :student_id')
            ->andWhere('date_format(d.date, \'%Y-%m-%d\') BETWEEN :minDate AND :maxDate');

        if($subject_id != null){
            $query = $query->andWhere('sub.id = :subject_id')
                ->setParameter('subject_id',$subject_id);
        }
        $query = $query->setParameter('student_id',$student_id)
            ->setParameter('maxDate',$dateMax)
            ->setParameter('minDate',$dateMin)
            ->getQuery()
            ->execute();
        return $query;
    }
}
