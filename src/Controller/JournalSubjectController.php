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
use App\Service\Helper;
use App\Service\Journal;
use Doctrine\Common\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Yectep\PhpSpreadsheetBundle\Factory;
use Yectep\PhpSpreadsheetBundle\PhpSpreadsheetBundle;
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
//dd($subject);
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

        if($this->isGranted('ROLE_STUDENT')){
            $dates =  $manager->getRepository(JournalDateMark::class)
                ->getOnByPage($subject->getId(),$request->get('page',0));

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent(Journal::Student($this->getUser()),$subject->getId(),$request->get('page',0));

        }else{

            $dates = $manager->getRepository(JournalDateMark::class)
                ->getOnByPage($subject->getId(),$request->get('page',0));

            foreach ($subject->getStudents() as $student) {
                $students[] = $manager->getRepository(JournalMark::class)
                    ->getOnMarksByStudent($student,$subject->getId(),$request->get('page',0));
            }
        }

        return $this->render('journal/journal_subject/subject.html.twig', [

            'subject' => $subject,
            'students' => $students,
            'typeMark' => $typeMark,
            'dates' => $dates,
            'totalPage' => $pages - 1,
            'menu'=>Helper::createMenu($manager),
        ]);

    }

    /**
     * @Route("/journal/group")
     */
    public function r(){return $this->redirect("/journal/");}

    /**
     * @Route("/journal/ajax/paginateSubject", name="paginateSubject", methods={"POST"})
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
     * @Route("/journal/ajax/showTableSubject", name="showTableSubject", methods={"POST"})
     */
    public function showTableSubject(Request $request,ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)
            ->find($request->get('subject_alis'));

        $students = [];

        if($this->isGranted('ROLE_STUDENT')){
            $dates =  $manager->getRepository(JournalDateMark::class)
                ->getOnByPage($subject->getId(),$request->get('page',0));

            $students[] = $manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent(Journal::Student($this->getUser()),$subject->getId(),$request->get('page',0));
        }else{

            $dates = $manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject->getId(),$request->get('page',0));

            foreach ($subject->getStudents() as $student) {
                $students[] = $manager->getRepository(JournalMark::class)
                    ->getOnMarksByStudent($student,$subject->getId(),$request->get('page',0));
            }
        }

        return $this->render('journal/journal_table/subject-table.html.twig',[
            'subject'=>$subject,
            'students'=>$students,
            'dates'=>$dates,
        ]);

    }

    /**
     * @Route("/journal/ajax/subjectShow", name="subjectShow", methods={"POST"})
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
     * @Route("/journal/ajax/dateMarkUpdate", name="dateMarkUpdate", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER')")
     */
    public function dateMarkUpdate(Request $request,ObjectManager $manager){
        $journalDateMark = $manager->getRepository(JournalDateMark::class)
            ->find($request->get('date_id'));
        $currentTeacher = Service\Journal::Teacher($this->getUser());
        if(!$this->isGranted('ROLE_ADMIN') &&
        $journalDateMark->getSubject()->getMainTeacher()->getId() != $currentTeacher->getId() ){
            return new JsonResponse(array('type' => 'error','message'=>'Недостатньо прав'));
        }

        if($request->get('date')=="" || $request->get('date')==null ) {
            $date=null;
        }else{
            $date = new \DateTime($request->get('date'));
        }

        if($date==null){
            $checkDate = $manager->getRepository(JournalDateMark::class)->checkDateOnEmpty($journalDateMark);
            if($checkDate) die(new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити пусту дату')));
        }

        $checkDate = $manager->getRepository(JournalDateMark::class)->checkDateOnMin($journalDateMark,$date);
        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити меншу дату'));
        }

        $checkDate = $manager->getRepository(JournalDateMark::class)->checkDateOnMax($journalDateMark,$date);
        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Неможливо поставити більшу дату'));
        }

        $checkDate = $manager->getRepository(JournalDateMark::class)->checkDateOnSkip($journalDateMark);
        if($checkDate){
            return new JsonResponse(array('type' => 'error','message'=>'Пропущено день'));
        }

        if($date==null){
            $typeMark = $manager->getRepository(JournalTypeMark::class)->findAll()[0];
        }else{
            $typeMark = $manager->getRepository(JournalTypeMark::class)->find( $request->get('type_mark_id'));
        }

        if($typeMark->getAverage() == 1){
            $students = $journalDateMark->getSubject()->getStudents();
            Service\Journal::setAttestation($students,$manager,$journalDateMark);
        }

        $journalDateMark->setDescription($request->get('description'));
        $journalDateMark->setDate($date);
        $journalDateMark->setTypeMark($typeMark);
        $manager->persist($journalDateMark);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Дата оновлена'));

    }

    /**
     * @Route("/journal/ajax/dateGet", name="dateGet", methods={"POST"})
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
     * @Route("/journal/ajax/subjectNameGet", name="subjectNameGet", methods={"POST"})
     */
    public function subjectNameGet(Request $request,ObjectManager $manager)
    {
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));

        return new JsonResponse(array('name'=>$subject->getName()));

    }

    /**
     * @Route("/journal/ajax/markUpdate", name="markUpdate", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER')")
     */
    public function markUpdate(Request $request,ObjectManager $manager){

        $mark = $manager->getRepository(JournalMark::class)
            ->find($request->get('mark_id'));

        $currentTeacher = Service\Journal::Teacher($this->getUser());
        if(!$this->isGranted('ROLE_ADMIN') &&
            $mark->getDateMark()->getSubject()->getMainTeacher()->getId() != $currentTeacher->getId() ){
            return new JsonResponse(array('type' => 'error','message'=>'Недостатньо прав'));
        }

        $mark->setMark($request->get('mark'));
        $manager->persist($mark);
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Оцінка оновлена'));
    }

    /**
    * @Route("/journal/ajax/subjectPageAdd", name="subjectPageAdd", methods={"POST"})
    * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER')")
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
     * @Route("/journal/ajax/subjectAdd", name="subjectAdd", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
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
     * @Route("/journal/ajax/updateSubject", name="updateSubject", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
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
     * @Route("/journal/ajax/getSubjectStudents", name="getSubjectStudents", methods={"POST"})
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
     * @Route("/journal/ajax/deleteStudentFromSubject", name="deleteStudentFromSubject", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteStudentFromSubject(Request $request, ObjectManager $manager){
        Service\Journal::deleteStudentFromSubject($manager,$request->get('subject_id'),$request->get('student_id'));

        return new JsonResponse(array('type' => 'info','message'=>'Студента видалено.'));
    }


    /**
     * @Route("/journal/ajax/deleteSubject", name="deleteSubject", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
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
     * @Route("/journal/ajax/addStudentOnSubject", name="addStudentOnSubject", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
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

    /**
     * @Route("/journal/ajax/generateTableExcel{subject_id}", name="generateTableExcel", methods={"get"})
     * @Security("is_granted('ROLE_ADMIN') ")
     */
    public function generateTableExcel(Request $request, ObjectManager $manager,\Swift_Mailer $mailer){

        $subjectExcel = new Service\ExcelJournal($manager);
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));
        if (!$subject) {
            return $this->render('Journal/Exception/error404.html.twig', [

                'message_error' => 'Сторінка не знайдена'
            ]);
        }
        $name = $subject->getGroup()->getAlisEn().'+'.$subject->getAlisEn().'+'.date("Y-m-d H:i:s");

        $response = $subjectExcel->getSubjectJournal($subject->getGroup()->getId(),$subject->getId());

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$name.'.xls"');
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }

    /**
     * @Route("/journal/ajax/sendTableExcelToStudent", name="sendTableExcelToStudent", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function sendTableExcelToStudent(Request $request, ObjectManager $manager,\Swift_Mailer $mailer){

        $subjectExcel = new Service\ExcelJournal($manager);
        $subject = $manager->getRepository(JournalSubject::class)->find($request->get('subject_id'));
        Service\Helper::isEmpty($subject);

        $name = $subject->getGroup()->getAlisEn().'+'.$subject->getAlisEn().'+'.date("Y-m-d H-i-s");

        foreach ($subject->getStudents() as $student){
            if(mb_strlen($student->getEmail1())<3 && mb_strlen($student->getEmail2())<3) continue;
            $subjectExcel->sendSubjectJournal($subject->getId(),$student);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($subjectExcel->spreadsheet);

            $writer->save("excel/subject/$name.xlsx");

            Service\ExcelJournal::send($student,$name,$mailer);

        }
        return new JsonResponse(array('type' => 'info','message'=>'Журнал розісланий студентам.'));

    }

    /**
     * @Route("/journal/ajax/sendTableExcelToAllStudent", name="sendTableExcelToAllStudent", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function sendTableExcelToAllStudent(Request $request, ObjectManager $manager,\Swift_Mailer $mailer){

        $student = $manager->getRepository(JournalStudent::class)->find($request->get('student_id'));
        Service\Helper::isEmpty($student);
        $group = $student->getGroup();

            $subjectExcel = new Service\ExcelJournal($manager);
            if(mb_strlen($student->getEmail1())<3 && mb_strlen($student->getEmail2())<3)
                return new JsonResponse(array('type' => 'error','message'=>'Некоректні дані '.$student->getName()));
            $subjectExcel->sendAllSubjectGroup($group,$student);
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($subjectExcel->spreadsheet);

            $name = $group->getAlisEn().'+'.Service\Helper::createAlias($student->getName()).'+'.date("Y-m-d H-i-s");
            $writer->save("excel/subject/$name.xlsx");
            $writer->save("excel/subject/$name.xlsx");

           Service\ExcelJournal::send($student,$name,$mailer);

        return new JsonResponse(array('type' => 'info','message'=>'Журнал відіслано студенту '.$student->getName()));

    }
}
