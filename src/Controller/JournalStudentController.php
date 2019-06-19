<?php

namespace App\Controller;

use App\Entity\JournalCode;
use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalTypeMark;
use App\Service\Helper;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\DateTime;

class JournalStudentController extends AbstractController
{
    /**
     * @Route("/journal/group/{group_alis}/students", name="journal_student_list")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listStudent(Request $request, ObjectManager $manager)
    {

        $journalGroup = $manager->getRepository(JournalGroup::class)->getGroupByAlis($request->get('group_alis'));

        if(!$journalGroup){
            return $this->render('journal/Exception/error404.html.twig',['message_error'=>'Така група не інуснує.']);
        }

        return $this->render('journal/journal_student/list-student.html.twig', [
            'group'=>$journalGroup,
            'controller_name' => 'JournalStudentController',
            'menu'=>Helper::createMenu($manager),
        ]);
    }

    /**
     * @Route("/journal/ajax/showStudent", name="showStudent")
     */
    public function listStudentTable(Request $request, ObjectManager $manager)
    {
        $students = $manager->getRepository(JournalStudent::class)->createQueryBuilder('s')
            ->Join('s.group','sg')
            ->where('sg.id = :group_id')
            ->setParameter('group_id',$request->get('group_id'))
            ->orderBy('s.name','asc')
            ->getQuery()
            ->execute();

        $string = '';
        foreach ($students as $student){
            $string.= $this->render('journal/show-humans.html.twig', [
                'human'=>$student,
                'controller_name' => 'listStudentTable',
            ]);
        }

        return new Response($string);
    }

    /**
     * @Route("/journal/ajax/addStudent", name="addStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function addStudent(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));

        $code = new JournalCode();
        $code->setKeyP(Helper::createAlias($request->get('student_name')).'_'.Helper::generatePassword(30));
        $code->setRole('ROLE_STUDENT');
        $manager->persist($code);

        $student = new JournalStudent();
        $student->setName(trim($request->get('student_name')));
        $student->setGroup($group);
        $student->setCode($code);

        $manager->persist($student);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента додано.'));

    }

    /**
     * @Route("/journal/ajax/updateStudent", name="updateStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function updateStudent(Request $request, ObjectManager $manager)
    {


        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));
        if(!$student){
            return new JsonResponse(array('type' => 'error','message'=>'Судента не знайдено.'));
        }
        $student->setName(trim($request->get('student_name')));
        $student->setEmail1(trim($request->get('email1')));
        $student->setEmail2(trim($request->get('email2')));
        $manager->persist($student);

        if(!$student->getCode()){
            $code = new JournalCode();
            $code->setKeyP(trim($request->get('key')));
            $manager->persist($code);
            $student->setCode($code);
        }else{
            $code = $manager->getRepository(JournalCode::class)->find($student->getCode()->getId());
            $code->setKeyP(trim($request->get('key')));
            $manager->persist($code);
        }
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента змінено.'));
    }

    /**
     * @Route("/journal/ajax/deleteStudent", name="deleteStudent")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteStudent(Request $request, ObjectManager $manager)
    {

        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));
        if(!$student){
            return new JsonResponse(array('type' => 'error','message'=>'Судента не знайдено.'));
        }
        if($student->getCode()){
            $code = $manager->getRepository(JournalCode::class)->find($student->getCode()->getId());
            $user = $code->getUser();

            if($user){
                $user->removeRole('ROLE_STUDENT');
            }
            $manager->remove($code);
        }

        $manager->remove($student);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Судента видалено.'));

    }

    /**
     * @Route("/journal/group/{group_alis}/student/{student_alis}", name="showSubjectsStudent" )
     */
    public function showSubjectsStudent(Request $request, ObjectManager $manager)
    {
        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_alis'));
        if(!$student) return $this->render('Journal/Exception/error404.html.twig', [
            'message_error' => 'Студент не знайдений'
        ]);

        $dateMarkRange = $manager->getRepository(JournalDateMark::class)->getRangeDateOnBayStudent($student->getId());

        $date = $manager->getRepository(JournalDateMark::class)
            ->getDateOnByRange($student->getId(),null,$dateMarkRange[0]['dateMax'],$dateMarkRange[0]['dateMin']);
        $arrayDates= [];
        foreach ($date as $item){
            $arrayDates[] =  $item->getDate()->format('Y-m-d');
        }

        $arrayOfDatesAll = Helper::createDateRangeArray($dateMarkRange[0]['dateMin'],$dateMarkRange[0]['dateMax']);
        $disabledDate = [];
        foreach ($arrayOfDatesAll as $item){
            if(!in_array($item,$arrayDates)){
                $disabledDate[] = $item;
            }
        }
        $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll();

        return $this->render('journal/journal_student/subjects-one-student.html.twig', [
            'student'=>$student,
            'disabledDate'=>json_encode($disabledDate),
            'rangeDate'=>$dateMarkRange,
            'controller_name' => 'JournalStudentController',
            'typeMark'=>$typeMark,
            'menu'=>Helper::createMenu($manager),
        ]);
    }

    /**
     * @Route("/journal/ajax/getTableSubjectsStudent", name="getTableSubjectsStudent" )
     */
    public function getSubjectsStudent(Request $request, ObjectManager $manager)
    {
        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));

        Helper::isEmpty($student);

        $subjects = $student->getSubjects();
        $tables = [];
        $subjectString = '';
        foreach ($subjects as $itemSubject){
            $date = $manager->getRepository(JournalDateMark::class)
                ->getDateOnByRange($student->getId(),$itemSubject->getId(),$request->get('dateMax'),$request->get('dateMin'));
            if(!$date) continue;
            $marks = [];
            foreach ( $date as $itemDate) {
                $marks[] = $manager->getRepository(JournalMark::class)->createQueryBuilder('m')
                    ->leftJoin('m.dateMark','d')
                    ->leftJoin('m.student','s')
                    ->andWhere('d.id = :date_id')
                    ->andWhere('s.id = :student_id')
                    ->setParameter('date_id',$itemDate->getId())
                    ->setParameter('student_id',$student->getId())
                    ->getQuery()
                    ->execute();
            }
            $table = ($this->renderView('journal/journal_table/clean-subject-table.html.twig',
                array('student'=>$student,'subject'=>$itemSubject,'date'=>$date,'marks'=>$marks)
            ));

            $tables[] = array('table'=>$table,'subjectName'=>$itemSubject->getName());

        }

        return new JsonResponse($tables);
    }
}
