<?php

namespace App\Repository;

use App\Entity\JournalMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Service;


/**
 * @method JournalMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JournalMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JournalMark[]    findAll()
 * @method JournalMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalMarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JournalMark::class);
    }

    public function getOnMarksByStudent($student,$subject_id,$page = null){
       $marks = $this->createQueryBuilder('m')
           ->leftJoin('m.student','stud')
           ->leftJoin('m.subject','sub')
           ->leftJoin('m.dateMark','d')
           ->andWhere('stud.id = :student_id')
           ->andWhere('sub.id =  :subject_id')
           ->setParameter('subject_id', $subject_id);

        if($page !=null){
            $marks = $marks->andWhere('d.page =  :page')
                ->setParameter('page',$page);
        }
        $marks = $marks->setParameter('student_id',$student->getId())
           ->getQuery()
           ->execute();
        $convertName = Service\Helper::convertName($student->getName());
        return  array('student'=>$student,'studentName'=>$convertName,'mark'=>$marks);
    }

    public function getOnMarksByStudentForForm6($student,$date,$group_id){
        $marks = $this->createQueryBuilder('m')
            ->leftJoin('m.student','stud')
            ->leftJoin('m.subject','sub')
            ->leftJoin('sub.group','g')
            ->leftJoin('m.dateMark','d')
            ->andWhere('stud.id = :student_id')
            ->andWhere('g.id =  :group_id')
            ->andWhere('date_format(d.date, \'%Y-%m-%d\') = :date ')
            ->setParameter('group_id', $group_id)
            ->setParameter('date',$date)
            ->setParameter('student_id',$student->getId())
            ->getQuery()
            ->execute();

        return $marks;
    }
    // /**
    //  * @return JournalMark[] Returns an array of JournalMark objects
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
    public function findOneBySomeField($value): ?JournalMark
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
