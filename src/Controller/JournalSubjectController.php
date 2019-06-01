<?php

namespace App\Controller;

use App\Entity\JournalDateMark;
use App\Entity\JournalGradingSystem;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalSubject;
use App\Entity\JournalTeacher;
use App\Entity\JournalTypeFormControl;
use App\Entity\JournalTypeMark;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;

class JournalSubjectController extends AbstractController
{

    /**
     * @Route("/journal/group/{group_alis}/{subject_alis}/{page}",
     *   name="journal_show_subject",
     *  defaults={"page": "0"},
     *  requirements={
     *     "page": "\d+"
     * }
     *  )
     */
    public function index(Request $request, ObjectManager $manager)
    {

        $subject = $manager->getRepository(JournalSubject::class)
            ->getSubjectByAlis($request->get('group_alis'),$request->get('subject_alis'));

        if (!$subject) {
            return $this->render('Journal/Exception/error404.html.twig', [

                'message_error' => 'Сторінка не знайдена'
            ]);
        }

        $students = [];

        $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll();

        $pages = $manager->getRepository(JournalDateMark::class)->getCountPage($subject->getId());
        if ($pages - 1 < $request->get('page', 0)) {
            return $this->render('Journal/Exception/error404.html.twig', [

                'message_error' => 'Сторінка не знайдена'
            ]);
        }

        $dates = $manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject->getId(), $request->get('page', 0));

        foreach ($subject->getStudents() as $student) {

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student, $subject->getId(), $request->get('page'));

        }

        return $this->render('journal/journal_subject/subject.html.twig', [

            'subject' => $subject,
            'students' => $students,
            'typeMark' => $typeMark,
            'dates' => $dates,
            'totalPage' => $pages - 1,

        ]);

    }

    /**
     * @Route("/journal/group")
     */
    public function r(){return $this->redirect("/journal/");}

    /**
     * @Route("/journal/ajax/paginateSubject", name="paginateSubject")
     */
    public function paginateSubject(Request $request, ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)
            ->find($request->get('subject_alis'));

        $pages = $manager->getRepository(JournalDateMark::class)->getCountPage($subject->getId());

        return $this->render('journal/journal_subject/subject-paginate.html.twig', array(
            'page'=>$request->get('page'),
            'totalPage'=>$pages-1
        ));
    }



    /**
     * @Route("/journal/ajax/showTableSubject", name="showTableSubject")
     */
    public function showTableSubject(Request $request,ObjectManager $manager)
    {

        $subject = $manager->getRepository(JournalSubject::class)
            ->find($request->get('subject_alis'));

        $students = [];


        $dates = $manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject->getId(),$request->get('page',0));

        foreach ($subject->getStudents() as $student) {

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId(),$request->get('page',0));

        }

        return $this->render('journal/journal_subject/head-table.html.twig',[

            'subject'=>$subject,
            'students'=>$students,
            'dates'=>$dates,


        ]);

    }

    /**
     * @Route("/journal/ajax/subjectShow", name="subjectShow")
     */
    public function showBlockSubjects(Request $request,ObjectManager $manager){

        $subjects = $manager->getRepository(JournalTypeFormControl::class)
            ->getSubjectsOnGroup($request->get('group_id'));

        $string ='';

        return $this->render('journal/journal_group/one-group.html.twig',[
            'subjects'=>$string,

        ]);

    }

    /**
     * @Route("/journal/ajax/dateMarkUpdate", name="dateMarkUpdate")
     */
    public function dateMarkUpdate(Request $request,ObjectManager $manager){

        $date = $manager->getRepository(JournalDateMark::class)
            ->find($request->get('date_id'));


        if($request->get('date')=="" || $request->get('date')==null ) {
            $d=null;
        }else{
            $d = new \DateTime($request->get('date'));
        }

        if($d==null){

            $checkDate = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
                ->leftJoin('d.subject','s')
                ->andWhere('s.id = :subject_id')
                ->andWhere("(d.id > :date_id AND d.date IS NOT NULL) ")
                ->setParameter('date_id',$request->get('date_id'))
                ->setParameter('subject_id',$date->getSubject()->getId())
                ->getQuery()
                ->execute();

            if($checkDate){
                return new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити пусту дату'));

            }

        }

        $checkDate = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.date >= :date and d.id < :date_id')//выбираю  все дати которые >= текущей и стоят до этой даты
            ->setParameter('subject_id',$date->getSubject()->getId())
            ->setParameter('date',$d)
            ->setParameter('date_id',$request->get('date_id'))
            ->getQuery()
            ->execute();

        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити меншу дату'));
        }

        $checkDate = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.date <= :date and d.id > :date_id')//выбираю  все дати которые >= текущей и стоят до этой даты
            ->setParameter('subject_id',$date->getSubject()->getId())
            ->setParameter('date',$d)
            ->setParameter('date_id',$request->get('date_id'))
            ->getQuery()
            ->execute();

        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити більшу дату'));
        }


        $checkDate = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere("(d.id < :date_id AND d.date IS NULL) ")
            ->setParameter('date_id',$request->get('date_id'))
            ->setParameter('subject_id',$date->getSubject()->getId())
            ->getQuery()
            ->execute();

        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Пропущено день'));
        }

        if($d==null){

            $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll()[0];

        }else{
            $typeMark = $manager->getRepository(JournalTypeMark::class)->find( $request->get('type_mark_id'));

        }

        if($typeMark->getAverage() == 1){
            $students = $date->getSubject()->getStudents();

            foreach ($students as $s){
                $marks = $manager->getRepository(JournalMark::class)->createQueryBuilder('m')
                    ->leftJoin('m.student','stud')
                    ->leftJoin('m.subject','sub')
                    ->leftJoin('m.dateMark','d')
                    ->andWhere('stud.id = :student_id')
                    ->andWhere('sub.id = :subject_id')
                    ->andWhere('d.id < :date_id')
                    ->setParameter('student_id',$s->getId())
                    ->setParameter('date_id',$date->getId())
                    ->setParameter('subject_id',$date->getSubject()->getId())
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
                    ->setParameter('date_id',$date->getId())
                    ->setParameter('subject_id',$date->getSubject()->getId())
                    ->getQuery()
                    ->execute()[0];
                $average = $counter!=0?$average/$counter:$average;
                $m->setMark(ceil($average));
                $manager->persist($m);

            }
            $manager->flush();

        }

        $date->setDescription($request->get('description'));
        $date->setDate($d);
        $date->setTypeMark($typeMark);
        $manager->persist($date);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Дата оновлена'));

    }

    /**
     * @Route("/journal/ajax/dateGet", name="dateGet")
     */
    public function dateGet(Request $request,ObjectManager $manager)
    {
        $date = $manager->getRepository(JournalDateMark::class)->find($request->get('date_id'));
        if($date->getDate()!=null){
            $d = $date->getDate()->format('Y-m-d');
        }else{
            $d = null;
        }
        return new JsonResponse(
            array(
            'description' => $date->getDescription(),
            'date'=> $d,
            'type_mark_id'=>$date->getTypeMark()->getId()
            )
        );
    }

    /**
     * @Route("/journal/ajax/subjectNameGet", name="subjectNameGet")
     */
    public function subjectNameGet(Request $request,ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));

        return new JsonResponse(array('name'=>$subject->getName()));

    }

    /**
     * @Route("/journal/ajax/markUpdate", name="markUpdate")
     */
    public function markUpdate(Request $request,ObjectManager $manager){

        $mark = $manager->getRepository(JournalMark::class)
            ->find($request->get('mark_id'));


        $mark->setMark($request->get('mark'));
        $manager->persist($mark);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Оцінка оновлена'));

    }

    /**
     * @Route("/journal/ajax/subjectPageAdd", name="subjectPageAdd")
     */
    public function addPageSubject(Request $request, ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));
        $pages = $manager->getRepository(JournalDateMark::class)->getCountPage($subject->getId());

        $lastDateLastPage = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
            ->leftJoin('d.subject','s')
            ->andWhere('s.id = :subject_id')
            ->andWhere('d.page = :page')
            ->setParameter('subject_id',$request->get('subject_id'))
            ->setParameter('page',$pages-1)
            ->orderBy('d.id','desc')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

        if($lastDateLastPage[0]->getDate() == null){
            return new JsonResponse(array('type' => 'error','message'=>'Спочатку запвоніть журнал','page'=>$pages-1));

        }

        $arrayIdStudentSubject = [];
        foreach ($subject->getStudents() as $student) {
            $arrayIdStudentSubject[] = $student->getId();
        }

        $typeMark = $manager->getRepository(JournalTypeMark::class)->findOneBy(array('name'=>'Оцінка'));

        Service\Journal::createPageJournal($pages,$typeMark,$subject,$arrayIdStudentSubject,$manager);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Сторінку журнала створено','page'=>$pages));

    }

    /**
     * @Route("/journal/ajax/subjectAdd", name="subjectAdd")
     */
    public function addSubject(Request $request, ObjectManager $manager)
    {
        $listStudent = json_decode($request->get('list_student'));
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));//$request->get('group_id'));
        $teacher = $manager->getRepository(JournalTeacher::class)->find($request->get('teacher_id'));//$request->get('teacher_id'));
        $typeMark = $manager->getRepository(JournalTypeMark::class)->findOneBy(array('name'=>'Оцінка'));
        $formControl = $manager->getRepository(JournalTypeFormControl::class)->find($request->get('form_control_id'));
        $grading_system_id = $manager->getRepository(JournalGradingSystem::class)->find($request->get('grading_system_id'));

        if(mb_strlen($request->get('name_subject')) <= 3 ){
            return new JsonResponse(array('type' => 'error','message'=>'Занадто коротка назва'));

        }

        $checkNameSubject = $manager->getRepository(JournalSubject::class)
            ->checkForUniqueness($request->get('name_subject'),$group->getId());

        if(count(($checkNameSubject))!=0){
            return new JsonResponse(array('type' => 'error','message'=>'Предмет з таким іменем вже існує.'));

        }

        $subject = new JournalSubject();
        $subject->setName($request->get('name_subject'));//$request->get('name_subject'));
        $subject->setDescription('3');
        $subject->setMainTeacher($teacher);
        $subject->setGroup($group);
        $subject->setTypeFormControl($formControl);
        $subject->setGradingSystem($grading_system_id);

        $manager->persist($subject);

        Service\Journal::createPageJournal(0,$typeMark,$subject,$listStudent,$manager);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Предмет створено.'));
    }


    /**
     * @Route("/journal/ajax/updateSubject", name="updateSubject")
     */
    public function updateSubject(Request $request, ObjectManager $manager)
    {

        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));
        $group = $manager->getRepository(JournalGroup::class)->find($subject->getGroup()->getId());
        $teacher = $manager->getRepository(JournalTeacher::class)->find($request->get('teacher_id'));


        if(mb_strlen($request->get('name_subject')) <= 3 ){
            return new JsonResponse(array('type' => 'error','message'=>'Занадто коротка назва'));

        }

        $checkNameSubject = $manager->getRepository(JournalSubject::class)
            ->checkForUniqueness($request->get('name_subject'),$group->getId(),$subject->getId());

        if(count(($checkNameSubject))!=0){
            return new JsonResponse(array('type' => 'error','message'=>'Предмет з таким іменем вже існує.'));

        }

        $subject->setName($request->get('name_subject'));//$request->get('name_subject'));
        $subject->setMainTeacher($teacher);

        $manager->persist($subject);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Предмет оновлено.'));
    }

    /**
     * @Route("/journal/ajax/getSubjectStudents", name="getSubjectStudents")
     */
    public function getSubjectStudents(Request $request, ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));

        $arrayIdStudentSubject = [];
        foreach ($subject->getStudents() as $student) {
            $arrayIdStudentSubject[] = $student->getId();
        }

        $group = $manager->getRepository(JournalGroup::class)->find($subject->getGroup()->getId());

        $arrayIdStudentGroup = [];
        foreach ($group->getStudents() as $student) {
            $arrayIdStudentGroup[] = $student->getId();
        }

        $arrayIdStudentNotSubject = [];

        foreach ($arrayIdStudentGroup as $item){
            if(!in_array($item,$arrayIdStudentSubject))
            $arrayIdStudentNotSubject[] = $item;
        }
        $studentsNotSubject = [];

        foreach ($arrayIdStudentNotSubject as $item) {
            $studentsNotSubject[] = $manager->getRepository(JournalStudent::class)->find($item);
        }


        $tables = $this->render('journal/journal_subject/studentOnBySubject.html.twig',array(
         'studentsYes'=>$subject->getStudents(),
         'studentsNo'=>$studentsNotSubject
        ));

        return ($tables);
    }

    /**
     * @Route("/journal/ajax/deleteStudentFromSubject", name="deleteStudentFromSubject")
     */
    public function deleteStudentFromSubject(Request $request, ObjectManager $manager){
        Service\Journal::deleteStudentFromSubject($manager,$request->get('subject_id'),$request->get('student_id'));


        return new JsonResponse(array('type' => 'info','message'=>'Студента видалено.'));
    }


    /**
     * @Route("/journal/ajax/deleteSubject", name="deleteSubject")
     */
    public function deleteSubject(Request $request, ObjectManager $manager){

        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));

        foreach ($subject->getStudents() as $student){

            Service\Journal::deleteStudentFromSubject($manager,$subject->getId(),$student->getId());

        }


        foreach ($subject->getDateMarks() as $date){

           $manager->remove($date);

        }

        $manager->remove($subject);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Предмет видалено.'));
    }

    /**
     * @Route("/journal/ajax/addStudentOnSubject", name="addStudentOnSubject")
     */
    public function addStudentOnSubject(Request $request, ObjectManager $manager){

        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));

        $listStudent = [];

        $listStudent[] =  $request->get('student_id');

        $listDate = $manager->getRepository(JournalDateMark::class)->createQueryBuilder('d')
            ->leftJoin('d.subject','sub')
            ->andWhere('sub.id = :subject_id')
            ->setParameter('subject_id',$request->get('subject_id'))
            ->getQuery()
            ->execute();


        Service\Journal::addStudentOnSubject($manager,$subject,$listStudent,$listDate,0);

        $manager->flush();
        return new JsonResponse(array('type' => 'info','message'=>'Студента видалено.'));
    }

}
