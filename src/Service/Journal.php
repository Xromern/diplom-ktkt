<?php


namespace App\Service;


use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalSubject;
use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\User;


class Journal
{
    /**
     * Получить учителя по пользователю
     */
    public static function Teacher(User $user){
        if(in_array('ROLE_TEACHER',$user->getRoles())){
            return $user->getCode()->getTeacher();
        }else{
           return false;
        }
    }

    public static function Student(User $user){
        if(in_array('ROLE_STUDENT',$user->getRoles())){
            return $user->getCode()->getStudent();
        }else{
            return false;
        }
    }

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

    public static function setAttestation($students,&$manager,$journalDateMark){
        foreach ($students as $s){
            $marks = $manager->getRepository(JournalMark::class)->createQueryBuilder('m')
                ->leftJoin('m.student','stud')
                ->leftJoin('m.subject','sub')
                ->leftJoin('m.dateMark','d')
                ->andWhere('stud.id = :student_id')
                ->andWhere('sub.id = :subject_id')
                ->andWhere('d.id < :date_id')
                ->setParameter('student_id',$s->getId())
                ->setParameter('date_id',$journalDateMark->getId())
                ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
                ->getQuery()
                ->execute();
            $average = 0;$counter = 0;
            foreach ($marks as $mark){
                if(is_numeric($mark->getMark())){
                    $average+=$mark->getMark();
                    $counter++;
                }
                if($mark->getDateMark()->getTypeMark()->getAverage()==1){
                    $average=0;
                    $counter=0;
                }
            }

            $m = $manager->getRepository(JournalMark::class)->createQueryBuilder('m')
                ->leftJoin('m.student','stud')
                ->leftJoin('m.subject','sub')
                ->leftJoin('m.dateMark','d')
                ->andWhere('stud.id = :student_id')
                ->andWhere('sub.id = :subject_id')
                ->andWhere('d.id = :date_id')
                ->setParameter('student_id',$s->getId())
                ->setParameter('date_id',$journalDateMark->getId())
                ->setParameter('subject_id',$journalDateMark->getSubject()->getId())
                ->getQuery()
                ->execute()[0];
            $average = $counter!=0?$average/$counter:$average;
            $m->setMark(ceil($average));
            $manager->persist($m);
        }
    }

    public static function getForm6($studentsArray,int $cal_days_in_month,&$manager,$date){
        $students = [];
        foreach ($studentsArray as $student) {
            $missedArray = array();
            for($i = 1;$i<=$cal_days_in_month;$i++){
                $marks = $manager->getRepository(JournalMark::class)
                    ->getOnMarksByStudentForForm6($student,($date .'-'. ( ($i<10)?'0'.$i:$i) ) ,$student->getGroup()->getId());
                $missedHours = $missed = 0;
                $arrayIdMark = [];
                foreach ($marks as $mark){
                    if($mark->getMark() == 'Н'){
                        $missedHours += 2;
                        if( $mark->getMissed() == 1) $missed = 1 ;
                        if( $mark->getMissed() == 2) $missed = 2 ;
                        $arrayIdMark[] = $mark->getId();
                    }
                }
                $missedArray[] = array('hours' => $missedHours, 'missed' => $missed, 'jsonId'=>json_encode($arrayIdMark));
            }
            $convertName = Helper::convertName($student->getName());
            $array = array('student'=>$student,'studentName'=>$convertName,'day'=>$missedArray);
            $students[] = $array;
        }
        return $students;
    }

}