<?php


namespace App\Service;


use App\Entity\JournalDateMark;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalSubject;
use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;

class Journal
{

    public static function createPageJournal(int $page,JournalTypeMark $typeMark, JournalSubject $subject,$listStudent,&$manager){
        $listDate = [];
        for($i = 0; $i<30; $i++){
            $listDate[$i] = new JournalDateMark();
            $listDate[$i]->setTypeMark($typeMark);
            $listDate[$i]->setSubject($subject);
            $listDate[$i]->setColor('#ffffff');
            $listDate[$i]->setPage($page);
            $manager->persist($listDate[$i]);
        }

        self::addStudentOnSubject($manager,$subject,$listStudent,$listDate,$page);

    }

    public static function deleteStudentFromSubject(ObjectManager &$manager,int $subject_id,int $student_id){

        $student = $manager->getRepository(JournalStudent::class)->find($student_id);

        $marks = $manager->getRepository(JournalMark::class)->createQueryBuilder('m')
            ->leftJoin('m.student','stud')
            ->leftJoin('m.subject','sub')
            ->andWhere('stud.id = :student_id')
            ->andWhere('sub.id  = :subject_id')
            ->setParameter('student_id',$student_id)
            ->setParameter('subject_id',$subject_id)
            ->getQuery()
            ->execute();

        $subject = $manager->getRepository(JournalSubject::class)->find($subject_id);

        $student->removeSubject($subject);

        foreach ($marks as $m){
            $manager->remove($m);
        }


        $manager->persist($subject);

        $manager->flush();

    }

    public static function addStudentOnSubject(ObjectManager &$manager,JournalSubject &$subject,array $listStudent,$listDate,$page){

        foreach ($listStudent as $student_id){
            $student = $manager->getRepository(JournalStudent::class)->find($student_id);
            if($page == 0) $student->setSubjects($subject);
            $manager->persist($student);
            foreach ($listDate as $date){
                $mark = new JournalMark();
                $mark->setDateMark($date);
                $mark->setSubject($subject);
                $mark->setStudent($student);
                $manager->persist($mark);
            }
        }

    }


}