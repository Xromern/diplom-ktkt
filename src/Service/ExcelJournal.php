<?php


namespace App\Service;


use App\Entity\JournalDateMark;
use App\Entity\JournalGroup;
use App\Entity\JournalMark;
use App\Entity\JournalSubject;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Validator\ValidatorBuilder;
use Yectep\PhpSpreadsheetBundle\Factory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class ExcelJournal
{
    private $manager;
    public $sheet;
    public $spreadsheet;
    public $factory;
    public $countSheet;
    public $count_sheet = 1;

    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->factory = new Factory();
        $this->spreadsheet =  $this->factory->createSpreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function sendSubjectJournal(int $subject_id,$student){

        $dates =  $this->manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject_id);

        $students['marks'] = $this->manager->getRepository(JournalMark::class)
            ->getOnMarksByStudent($student,$subject_id);


        $this->createHeadTable(count($dates));
        $this->getDateTable($dates);
        $this->getStudentsTable($students);

        return $this->factory->createStreamedResponse($this->spreadsheet, 'Xls');

    }

    public function sendAllSubjectGroup(JournalGroup $group,$student){

        foreach ($group->getSubjects() as $subject){
            $this->sheet->setTitle($subject->getName());
            $this->sendSubjectJournal($subject->getId(),$student);
            $this->sheet= $this->spreadsheet->createSheet();
        }
    }

    public function getSubjectJournal(int $group_id,int $subject_id){
        $subject =  $this->manager->getRepository(JournalSubject::class)
            ->getSubjectByAlis($group_id,$subject_id);

        $dates =  $this->manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject->getId());
        $students = [];
        foreach ($subject->getStudents() as $student) {
            $students[] = $this->manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId());
        }

        $this->createHeadTable(count($dates));
        $this->getDateTable($dates);
        $this->getStudentsTable($students);

        return $this->factory->createStreamedResponse($this->spreadsheet, 'Xls');
    }

    function createHeadTable($countDate){
        $this->sheet->setCellValue("A1", ('id'));
        $this->sheet->mergeCells('A1:A3');//Объяденение ячеек //HORIZONTAL_LEFT
        $this->c_setHorizontal($this->sheet, 'A1');
        $this->c_setVertical($this->sheet, 'A1');
        $this->sheet->getColumnDimension('A')->setWidth(3); //Ширина ячейки
        $this->sheet->getStyle("A1")->getFont()->setBold(true);    //Шрифт жирным

        $this->sheet->setCellValue("B1", ('Прізвище та ініціали'));
        $this->sheet->mergeCells('B1:B3');//Объединение  ячейки
        $this->c_setHorizontal($this->sheet, 'B1');
        $this->c_setVertical($this->sheet, 'B1');
        $this->sheet->getColumnDimension('B')->setAutoSize(true);
        $this->sheet->getStyle("B1")->getFont()->setBold(true);

        $colString = Coordinate::stringFromColumnIndex($countDate);

        $this->sheet->setCellValue("C1", ('Місяць, число'));
        $this->sheet->mergeCells('C1'.':'.$colString.'1');//Объединение  ячейки
        $this->c_setHorizontal($this->sheet, 'C1');
        $this->c_setVertical($this->sheet, 'C1');
        $this->sheet->getStyle("C1")->getFont()->setBold(true);
    }

    function getStudentsTable($students){
        $i=1;
        foreach ($students as $student){
            $this->sheet->setCellValueByColumnAndRow(1, $i+3/*Рядок*/, $i);
            $this->sheet->setCellValueByColumnAndRow(2, $i+3/*Рядок*/, $student['studentName']);
            $j=3;
            foreach ($student['mark'] as $mark){
                $this->sheet->setCellValueByColumnAndRow($j,$i+3, $mark->getMark());
                $this->c_setHorizontal($this->sheet, $i);
                $j++;
            }
            ++$i;
            $this->sheet->getRowDimension($i+2)->setRowHeight(20);
        }
    }

    function getDateTable($dates){
        $i = 3;
        foreach ($dates as $date) {
            $colString = Coordinate::stringFromColumnIndex($i);
            if($date->getDate()){
                $d = $date->getDate()->format('m d');
            }else{ $d ='';}
            $this->sheet->setCellValueByColumnAndRow($i, 2/*Рядок*/,$d);
            $this->sheet->mergeCells($colString . (2) . ":" . $colString . (3));
            $this->sheet->getColumnDimension($colString)->setWidth(3);
            $this->sheet->getRowDimension(3)->setRowHeight(20);
            $this->sheet->getStyle($colString . (2) . ":" . $colString . (3))->getAlignment()->setWrapText(true);
            $this->sheet->getStyle($colString . (2) . ":" . $colString . (3))->getFill()->getStartColor()->setRGB($date->getTypeMark()->getColor());
            $i++;
        }
    }

    function c_setHorizontal($sheet, $coordinates)
    {
        $sheet->getStyle($coordinates)->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );
    }

    function c_setVertical($sheet, $coordinates)
    {
        $sheet->getStyle($coordinates)->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );
    }
}