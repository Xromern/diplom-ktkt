<?php

namespace App\Controller;

use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalStudent;
use App\Entity\JournalSubject;
use App\Service\ExcelJournal;
use App\Service\Helper;
use App\Service\Journal;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Yectep\PhpSpreadsheetBundle\Factory;
use Yectep\PhpSpreadsheetBundle\PhpSpreadsheetBundle;


class Form6Controller extends AbstractController
{
    /**
     * @Route("/journal/group/{group_alis}/form6",
     *   name="journal_show_form6",
     *  )
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $group = $manager->getRepository(JournalGroup::class)->getGroupByAlis($request->get('group_alis'));
        if(!$group) return $this->render('journal/journal_subject/subject.html.twig', ['message_error'=>'Група не знайдена']);

        $dateDistinct  = $manager->getRepository(JournalDateMark::class)->getDistinctOnByGroup($group->getId());
        $option = '';

        $monthsList = array(
            "01" => "Січня",
            "02" => "Лютого",
            "03" => "Березная",
            "04" => "Квітня",
            "05" => "Травня",
            "06" => "Червня",
            "07" => "Липня",
            "08" => "Серпня",
            "09" => "Вересня",
            "10" => "Жовтня",
            "11" => "Листопада",
            "12" => "Грудня"
        );

        foreach ($dateDistinct as $item) {
            if ($item['dateFormat'] != null) {
                $dateArray = explode('-', $item['dateFormat']);
                $option .= '<option value="'.$item['dateFormat'].'">' . $item['dateFormat'] . ' ' . $monthsList[$dateArray[1]] . '</option>';
            }
        }
        $arrayStudent = [];

        foreach ($group->getStudents() as $student) {
            $arrayStudent[] = $student->getId();
        }

        return $this->render('journal/form6/form6.html.twig', [
            'group' => $group,
            'option'=> $option,
            'menu'=>Helper::createMenu($manager),
            'arrayStudent'=>json_encode($arrayStudent)
        ]);
    }

    /**
     * @Route("/journal/ajax/form6Table", name="form6Table")
     */
    public function getTable(Request $request, ObjectManager $manager)
    {
     //   dd($request->get('group_id'));
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        if(count($group->getSubjects())==0){
            return new JsonResponse(array('type' => 'info','message'=>'Форма 6 пуста'));
        }

        Helper::isEmpty($group);
        $dateArray = explode('-',$request->get('date'));

        $cal_days_in_month = cal_days_in_month(CAL_GREGORIAN, $dateArray[1], $dateArray[0]);

        if($this->isGranted('ROLE_STUDENT')){
            $students = Journal::getForm6([Journal::Student($this->getUser())],$cal_days_in_month,$manager,$request->get('date'));

        }else{
            $students = Journal::getForm6($group->getStudents(),$cal_days_in_month,$manager,$request->get('date'));
        }


        return $this->render('journal/journal_table/form6-table.html.twig',array(
            'students'=>$students,
            'group'=>$group,
            'date'=>$request->get('date'),
            'cal_days_in_month'=>$cal_days_in_month
        ));
    }

    /**
     * @Route("/journal/ajax/form6UpdateMissed", name="form6UpdateMissed")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER') ")
     */
    public function updateMissed(Request $request, ObjectManager $manager)
    {

        $missed = $request->get('missed');
        if($this->isGranted('ROLE_TEACHER')){
            $teacher =  Journal::Teacher($this->getUser());
            $teacher->getGroup();
           // dd($request);
        }


        foreach ($request->get('mark_id') as $item){
            $mark = $manager->getRepository(JournalMark::class)->find($item);
            Helper::isEmpty($mark);
            $missed++;
            if($missed > 2)  $missed = 0;
            $mark->setMissed($missed);
            $manager->persist($mark);
        }
        $manager->flush();

        return new JsonResponse(array('type' => 'info','message'=>'Пропуск оновлено'));
    }

    /**
     * @Route("/journal/ajax/generateTableExcelForm6/{group_id}/{date}", name="generateTableExcelForm6", methods={"get"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER') ")
     */
    public function generateTableExcelForm6(Request $request, ObjectManager $manager,\Swift_Mailer $mailer){

        $subjectExcel = new ExcelJournal($manager);
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        if (!$group) {
            return $this->render('Journal/Exception/error404.html.twig', [

                'message_error' => 'Сторінка не знайдена'
            ]);
        }

        $dateArray = explode('-',$request->get('date'));

        $cal_days_in_month = cal_days_in_month(CAL_GREGORIAN, $dateArray[1], $dateArray[0]);

        $students = Journal::getForm6($group->getStudents(),$cal_days_in_month,$manager,$request->get('date'));


        $name ='Form6+'.$group->getAlisEn().'+'.$request->get('date');

        $response = $subjectExcel->getForm6($students,$cal_days_in_month);

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$name.'.xls"');
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }

    /**
     * @Route("/journal/ajax/sendFormToAllStudent/", name="sendFormToAllStudent", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN') ")
     */
    public function sendFormToAllStudent(Request $request, ObjectManager $manager,\Swift_Mailer $mailer){

        $subjectExcel = new ExcelJournal($manager);
        $group = $manager->getRepository(JournalGroup::class)->find($request->get('group_id'));
        $student = [$manager->getRepository(JournalStudent::class)->find($request->get('student_id'))];

        Helper::isEmpty($group); Helper::isEmpty($student[0]);

        $dateArray = explode('-',$request->get('date'));

        $cal_days_in_month = cal_days_in_month(CAL_GREGORIAN, $dateArray[1], $dateArray[0]);

        $students = Journal::getForm6($student,$cal_days_in_month,$manager,$request->get('date'));

        $name = 'Form6+'.$group->getAlisEn().'+'.Helper::createAlias($student[0]->getName()).'+'.$request->get('date');

        $subjectExcel->getForm6($students,$cal_days_in_month);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($subjectExcel->spreadsheet);

        $writer->save("excel/subject/$name.xlsx");

        return ExcelJournal::send($student[0],$name,$mailer);
 }

}
