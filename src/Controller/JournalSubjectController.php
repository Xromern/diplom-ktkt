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

        foreach ($subject->getStudents() as $student) {

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId(),$page = 0);

        }

        return $this->render('journal/journal_subject/subject.html.twig',[

            'subject'=>$subject,
            'students'=>$students,

        ]);



    }

    /**
     * @Route("/journal/ajax/showTableSubject", name="showTableSubject")
     */
    public function showTableSubject(Request $request,ObjectManager $manager)
    {


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

        $typeMark = $manager->getRepository(JournalTypeMark::class)->find($request->get('type_mark_id'));
        $d = new \DateTime($request->get('date'));
        $date->setDescription($request->get('description'));
        $date->setDate($d);
        $date->setColor($request->get('color'));
        $date->setTypeMark($typeMark);
        $manager->persist($date);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Дата оновлена'));

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

        if(strlen($request->get('name_subject') <= 2 )){
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
