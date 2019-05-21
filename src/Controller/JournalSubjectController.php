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
        foreach ($subject->getStudents() as $item) {

            $mark = $manager->getRepository(JournalMark::class)
                ->createQueryBuilder('m')
                ->leftJoin('m.student','stud')
                ->leftJoin('m.subject','sub')
                ->leftJoin('m.dateMark','d')
                ->andWhere('d.page =  :page')
                ->andWhere('stud.id = :student_id')
                ->andWhere('sub.id =  :subject_id')
                ->setParameter('subject_id', 20)
                ->setParameter('page',0)
                ->setParameter('student_id',$item->getId())
                ->getQuery()
                ->execute();
                $convertName = Service\Helper::convertName($item->getName());
                $array = array('student'=>$item,'studentName'=>$convertName,'mark'=>$mark);
                $students[] =$array;

        }
//        dd($students);

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
     * @Route("/journal/group/{group_alis}", name="journal_show_one_group")
     */
    public function showSubjects(Request $request,ObjectManager $manager)
    {


        $journalGroup = $manager->getRepository(JournalGroup::class)
            ->getGroupByAlis($request->get('group_alis'));

        $gradingSystem = $manager->getRepository(JournalGradingSystem::class)->findAll();

        $subjects = $manager->getRepository(JournalTypeFormControl::class)
            ->getSubjectsOnGroup($journalGroup->getId());

//        dd($subjects);
        $formControl = $manager->getRepository(JournalTypeFormControl::class)->findAll();
        return $this->render('journal/journal_group/one-group.html.twig',[
            'group'=>$journalGroup,
            'formControl'=>$formControl,
            'subjects'=>$subjects,
            'gradingSystem'=>$gradingSystem
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

        $subject = new JournalSubject();
        $subject->setName($request->get('name_subject'));//$request->get('name_subject'));
        $subject->setDescription('3');
        $subject->setMainTeacher($teacher);
        $subject->setGroup($group);
        $subject->setTypeFormControl($formControl);
        $subject->setGradingSystem($grading_system_id);

        $manager->persist($subject);

//        if(count($listStudent) != 0)
        Service\Journal::createJournal($typeMark,$subject,$listStudent,$manager);

        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Предмет створено.'));
    }
}
