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
    public static function createJournal(JournalTypeMark $typeMark, JournalSubject $subject,$listStudent,&$manager){
        $listDate = [];
        for($i = 0; $i<30; $i++){
            $listDate[$i] = new JournalDateMark();
            $listDate[$i]->setTypeMark($typeMark);
            $listDate[$i]->setSubject($subject);
            $manager->persist($listDate[$i]);
        }

       foreach ($listStudent as $student_id){
           $student = $manager->getRepository(JournalStudent::class)->find($student_id);
           $student->setSubjects($subject);
           $manager->persist($student);
           foreach ($listDate as $date){
               $mark = new JournalMark();
               $mark->setDateMark($date);
               $mark->setStudent($student);
               $manager->persist($mark);
           }
       }


    }

}