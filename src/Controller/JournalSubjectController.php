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
     * @Route("/journal/group/{group_alis}/{subject_alis}", name="journal_show_subject")
     */
    public function index(Request $request,ObjectManager $manager)
    {

        $subject = $manager->getRepository(JournalSubject::class)
            ->find($request->get('subject_alis'));

        $students = [];

        $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll();

        foreach ($subject->getStudents() as $student) {

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId(),$page = 0);

        }



        return $this->render('journal/journal_subject/subject.html.twig',[

            'subject'=>$subject,
            'students'=>$students,
            'typeMark'=>$typeMark

        ]);



    }

    /**
     * @Route("/journal/ajax/showTableSubject", name="showTableSubject")
     */
    public function showTableSubject(Request $request,ObjectManager $manager)
    {

        $subject = $manager->getRepository(JournalSubject::class)
            ->find($request->get('subject_alis'));

        $students = [];

        foreach ($subject->getStudents() as $student) {

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId(),$page = 0);

        }

        return $this->render('journal/journal_subject/head-table.html.twig',[

            'subject'=>$subject,
            'students'=>$students,

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
            ->andWhere('d.date >= :date')
            ->andWhere('d.id < :date_id')
            ->orWhere('d.id > :date_id and :date > d.date')
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

        Service\Journal::createJournal($typeMark,$subject,$listStudent,$manager);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Предмет створено.'));
    }

}
